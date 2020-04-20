<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use App\FBAccount;
use Curl;
use Session;

class FeedController extends Controller
{
	public function getPosts(Request $request, $p_uid, $uids, $limit_posts, $limit_comments) {
		if (empty($uids)) {
			return response('Bạn chưa điền ID người lấy bài viết!', 422);
		}

    	$url = mkurl(true, 'graph.facebook.com', 'feed', [
    		'ids' => $uids,
    		'fields' => ($limit_comments === 0 ? 'id,actions' : "id,actions,comments.limit($limit_comments){can_comment}"),
    		'limit' => $limit_posts,
			'access_token' => $request->account['access_token']
		]);
    	$data = json_decode(Curl::to($url)->get(), true);
    	if (empty($data) || !empty($data['error'])) {
    		return response('Có lỗi khi lấy bài viết!', 500);
    	}
    	$object_id = [];
    	foreach ($data as $id => $value) {
    		foreach ($value['data'] as $item) {
    			if ($item['actions'][0]['name'] === 'Comment') {
	    			// lấy những bài viết có thể comment
					array_push($object_id, $item['id']);
    				if (!empty($item['comments'])) {
    					// lấy những comment có thể reply
    					foreach ($item['comments']['data'] as $comment) {
    						if ($comment['can_comment'] === true) {
    							array_push($object_id, $comment['id']);
    						}
    					}
    				}
    			}
    		}
    	}

    	return $object_id;
    }
	
	public function getURLFile(Request $request) {
		if ($request->file->isValid()) {
			$link = upanh($request->file->getPathname());
			return response($link, 200);
		}
	}

	private function postUnPublishedPhotos($url_picture, $access_token) {
    	$url = mkurl(true, 'graph.facebook.com', 'me/photos', [
    		'url' => $url_picture,
    		'published' => 'false',
    		'access_token' => $access_token
    	]);
    	$data = json_decode(Curl::to($url)->post(), true);
    	if (!empty($data['error'])) {
    		return ['is_success' => false, 'message' => $data['error']['message']];
    	}
    	return $data['id'];
    }

    private function replies_posts($sender) {
    	extract($sender, EXTR_PREFIX_SAME, 'wddx');

    	// $m_facebook = 'https://m.facebook.com/';
    	// preg_match('/action="(.+?)"/m', $curl, $link);
    	$url = mkurl(true, 'graph.facebook.com', "$object_id/comments", [
    		'message' => $comment,
    		'attachment_url' => $picture,
    		'access_token' => $account['access_token']
    	]);
    	$curl = json_decode(Curl::to($url)->post(), true);
    	if (!empty($curl['error'])) {
    		return false;
    	}
    	return true;
    }

    private function replies_comment($request, $sender) {
    	extract($sender, EXTR_PREFIX_SAME, 'wddx');

    	$m_facebook = 'https://m.facebook.com/';
    	$header = [
    		'Cookie: ' . $account['cookie'],
    		'User-Agent: ' . agent()
    	];
    	// nếu ko có dữ liệu fb_dtsg và jazoest thì Curl để lấy
    	if (empty($request->cookie('fb_dtsg')) && empty($request->cookie('jazoest'))) {
	        $curl = Curl::to($m_facebook . $object_id)
	        	->withHeaders($header)
	        	->allowRedirect()
	        	->get();
	        preg_match('/fb_dtsg" value="(.+?)".+?jazoest" value="(.+?)"/m', $curl, $data_send);
	        if (empty($data_send)) {
	        	return false;
	        }
    	} else {
    		$fb_dtsg = $request->cookie('fb_dtsg');
    		$jazoest = $request->cookie('jazoest');
    	}
        // lấy thông tin
        $data = [
    		'fb_dtsg' => $data_send[1] ?? $fb_dtsg,
    		'jazoest' => $data_send[2] ?? $jazoest,
    		'comment_text' => $comment,
    		'photo_ids' => $picture
    	];
    	$object = explode('_', $object_id);
    	$posts_id = $object[0];
    	$comment_id = $object[1];
    	$params = [
    		'parent_comment_id' => $comment_id,
    		'parent_redirect_comment_token' => $object_id,
    		'ft_ent_identifier' => $posts_id
    	];

    	$url = $m_facebook . 'a/comment.php?' . http_build_query($params);
		$curl = Curl::to($url)
			->withHeaders($header)
			->withData($data)
			->allowRedirect()
			->post();

		if (!preg_match('/'.$object_id.'/', $curl)) {
			// nếu ko comment được, nếu có 2 cookie thì flush và request again
			if ($request->cookie('fb_dtsg') && $request->cookie('jazoest')) {
				Cookie::forget('fb_dtsg');
				Cookie::forget('jazoest');
			}
			$this->replies_comment($request, $sender);
		}

		return ['fb_dtsg' => $data['fb_dtsg'], 'jazoest' => $data['jazoest']];
    }

    public function comment(Request $request) {
    	if (!empty($request->url_picture)) {
	        $picId = $this->postUnPublishedPhotos($request->url_picture, $request->account['access_token']);
	        if (!empty($picId['is_success']) && $picId['is_success'] === false) {
	        	return response($picId['message'], 500);
	        }
    	}

        // xem id là 1 bài viết hay 1 comment
        $url = mkurl(true, 'graph.facebook.com', $request->object_id, [
        	'access_token' => $request->account['access_token']
        ]);
        $curl = json_decode(Curl::to($url)->get(), true);
        if (!empty($curl['error'])) {
        	return response($curl['error']['message'], 500);
        }

        $sender = [
        	'object_id' => $request->object_id,
        	'comment' => $request->comment,
        	'picture' => '',
        	'account' => $request->account
        ];
        if (isset($curl['from'])) {
        	$sender['picture'] = $picId ?? null;
        	$status = $this->replies_comment($request, $sender);
        } else {
        	$sender['picture'] = $request->url_picture;
        	$status = $this->replies_posts($sender);
        }
        if ($status === false) {
        	return response('Không lấy được thông tin bài viết!', 500);
        }

        if (!empty($status['fb_dtsg']) && !empty($status['jazoest'])) {
        	return response(['Bình luận thành công!', $status['fb_dtsg']], 200)
        		->withCookie(cookie('fb_dtsg', $status['fb_dtsg'], 20))
        		->withCookie(cookie('jazoest', $status['jazoest'], 20));
        }
        return response('Bình luận thành công!', 200);
    }

	/*private function getReactions($url, array $reactions) {
		// get data and paging in feed
		$feed = json_decode(Curl::to($url)->get(), true);
		if (isset($feed['error'])) {
			return false;
		}
		if (!isset($feed['paging'])) {
			return $reactions;
		}
		$data_feed = $feed['data'];
		// lặp từng item trong $data và lấy cảm xúc từng người
		foreach ($data_feed as $item_feed) {
			if (!isset($item_feed['reactions'])) {
				continue;
			}
			// chỉ lấy những bài viết có reactions
			$data_reactions = $item_feed['reactions']['data'];
			foreach ($data_reactions as $item_reactions) {
				$reactions_type = strtolower($item_reactions['type']);
				$key = array_search_multidim($reactions, 'id', $item_reactions['id']);
				if ($key > -1) {
					$reactions[$key]['reactions'][$reactions_type] += 1;
				} else {
					$arr = [
						'id' => $item_reactions['id'],
						'name' => $item_reactions['name'],
						'picture' => $item_reactions['pic_small'],
						'link' => $item_reactions['link'],
						'is_friend' => 1,
						'reactions' => [
							'like' => 0,
							'love' => 0,
							'haha' => 0,
							'wow' => 0,
							'sad' => 0,
							'angry' => 0
						]
					];
					$arr['reactions'][$reactions_type] += 1;
				    array_push($reactions, $arr);
				}
			}
		}

		// đệ quy đến khi ko còn trang nào
		$page_next = $feed['paging']['next'];
		return $this->getReactions($page_next, $reactions);
	}

	public function reactions(Request $request) {
		if ($request->selectDateType >= 1 && $request->selectDateType <= 2) {
			$account = FBAccount::where('user_id', auth()->id())->first();
			$since = $request->selectDateType == 1 ? '-3 months' : '-6 months';
			$url = mkurl(true, 'graph.facebook.com', 'me/feed', [
				'fields' => 'reactions{name,pic_small,link,type}',
				'since' => strtotime($since),
				'access_token' => $account->access_token
			]);
			$reactions = $this->getReactions($url, []);
			if ($reactions == false) {
				return response('Có lỗi xảy ra!', 404);
			}
			return response($reactions, 200);
		}
		return response(null, 204);
	}

    public function getStatus(Request $request, $uid = null) {
		$socials = Social::where('user_id', Auth::user()->id)->get()->toArray();
		if ($uid == null) {
			return view('auto.status.getstatus', compact('socials'));
		}

		$user = Social::where('provider_uid', $uid)->get()->first();
		if (!$user) {
			return redirect('/facebook/status')->with('error', 'Có lỗi xảy ra, uid facebook không đúng !');
		}
		$user = $user->toArray();
		// lấy bài viết trên tường nhà
		$url_get_feed = mkurl(true, 'graph.facebook.com', "$uid/feed", [
			'fields' => 'id,message,name,description,link,source,story,type,full_picture,created_time,actions,privacy,comments',
			'access_token' => $user['access_token']
		]);
		$feed = Curl::to($url_get_feed)->get();
		$feed_data = str_replace('\\n', '<br />', $feed);
		$feed = json_decode($feed_data, true);
		if ($err_msg = CheckAndHandleFBErrCode($feed)) {
			return back()->with('error', $err_msg);
		}

		$stt_data = $feed['data'];
		$stt_page = $feed['paging']['next'];

		$request->session()->put('stt_page', $stt_page);

		return view('auto.status.getstatus', compact('user', 'socials', 'stt_data'));
	}

	public function postStatus(Request $request) {
		$socials = Social::where('user_id', Auth::user()->id)->get()->toArray();
		if (!$socials) {
			return view('home');
		}
		if ($request->uid == null) {
			return view('auto.status.poststatus', compact('socials'));
		}

		$social = Social::where('provider_uid', $request->uid)->get()->first()->toArray();
		if (!$social) {
			return back()->with('error', 'ID facebook không tồn tại trong hệ thống !');
		}

		$feed = null;
		if (empty($request->message) && empty($request->images)) {
			return back()->with('error', 'Đăng bài không thành công, bạn phải điền đầy đủ');
		} elseif (!empty($request->message) || $request->hasFile('images')) {
			if (!empty($request->message)) {
				$tmp = ['message' => $request->message];
			}
			$tmp['access_token'] = $social['access_token'];
			$fields = $tmp;
			if ($request->hasFile('images')) {
				$attached = $this->postUnpublishedPhotos($request->file('images'), $request->caption, $social);
				$fields = array_merge($tmp, ['tmp' => 'tmp']);
			}

			$url_post_stt = mkurl(true, 'graph.facebook.com', "$social[provider_uid]/feed", $fields);
			$feed = json_decode(Curl::to($url_post_stt)->post(), true);
			if ($err_msg = CheckAndHandleFBErrCode($feed)) {
				return back()->with('error', $err_msg);
			}

			$this->insertPostStt($social['id'], $request->message);
		} else {
			return back()->with('error', 'An error occurred. vui lòng liên hệ QTV để fix (:');
		}

		if ($err_msg = CheckAndHandleFBErrCode($feed)) {
			return back()->with('error', $err_msg);
		}

		return back()->with('success', 'Đăng bài thành công. <a href="https://fb.com/' . $feed['id'] . '" target="_blank">Ấn vào đây</a> để xem bài viết của bạn');
	}*/

	/*public function postUnPublishedPhotos($files, $captions, $social) {
		$photos = [];
		$attached_media = [];
		$media_fbid = [];
		$count_file = count($files);
		// upload image lên server và đăng bài viết với chế độ published=false
		for ($i=0; $i < $count_file; $i++) {
			if ($files[$i]->isValid()) {
				$url_picture = upanh($files[$i]->getPathname()); // tmp name
				$url_post_photos = mkurl(true, 'graph', 'facebook.com', "$social[provider_uid]/photos", null);

				$photos[$i] = json_decode(Curl::to($url_post_photos)->withData([
					'url' => $url_picture,
					'caption' => $captions[$i],
					'published' => 'false',
					'access_token' => $social['access_token'],
				])->post(), true)['id'];

				$attached_media[$i] = 'attached_media[' . $i . ']';
				$media_fbid[$i] = '{"media_fbid":"' . $photos[$i] . '"}';
			}
		}
		$attached = array_combine($attached_media, $media_fbid);
		return $attached;
	}*/
}
