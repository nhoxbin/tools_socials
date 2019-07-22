<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FBAccount;
use Curl;
use Session;

class FeedController extends Controller
{
	private $account;

	public function __construct(Request $request) {
		$p_uid = $request->p_uid;
		if (is_numeric($p_uid)) {
			$this->account = FBAccount::where('provider_uid', $p_uid)->first();
		}
	}

	public function getPosts($p_uid, $uids, $limit) {
    	if (empty($this->account)) {
			return response('Không tìm thấy tài khoản Facebook!', 404);
		}
		if (is_numeric($limit) && ($limit*2 < 5 || $limit*2 > 100)) {
			return response('Lấy từ 5 > 100 bài viết!', 500);
		}

    	$url = mkurl(true, 'graph.facebook.com', 'v3.3', [
    		'ids' => $uids,
    		'fields' => "feed.limit($limit){id}",
			'access_token' => $this->account->access_token
		]);
    	$data = json_decode(Curl::to($url)->withHeader('User-Agent', agent())->get(), true);
    	if (!empty($data['error'])) {
    		return response('Có lỗi khi lấy bài viết, kiểm tra lại ID!', 500);
    	}

    	$posts = [];
    	foreach ($data as $id => $value) {
    		$user_posts = array_column($value['feed']['data'], 'id');
    		foreach ($user_posts as $value) {
    			array_push($posts, $value);
    		}
    	}

    	return $posts;
    }
	
	private function getReactions($url, array $reactions) {
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
			$url = mkurl(true, 'graph.facebook.com', 'v3.3/me/feed', [
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
		$url_get_feed = mkurl(true, 'graph', 'facebook.com', "v2.9/$uid/feed", ['fields' => 'id,message,name,description,link,source,story,type,full_picture,created_time,actions,privacy,comments', 'access_token' => $user['access_token']]);
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

			$url_post_stt = mkurl(true, 'graph', 'facebook.com', "v2.9/$social[provider_uid]/feed", $fields);
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
	}

	public function postUnPublishedPhotos($files, $captions, $social) {
		$photos = [];
		$attached_media = [];
		$media_fbid = [];
		$count_file = count($files);
		// upload image lên server và đăng bài viết với chế độ published=false
		for ($i=0; $i < $count_file; $i++) {
			if ($files[$i]->isValid()) {
				$url_picture = upanh($files[$i]->getPathname()); // tmp name
				$url_post_photos = mkurl(true, 'graph', 'facebook.com', "v2.9/$social[provider_uid]/photos", null);

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
	}

	public function deleteStatus($uid, $idStatus) {
		$user = Social::where('provider_uid', $uid)->get()->first();
		if (!$user) {
			return back()->with('error', 'User không tồn tại trong hệ thống');
		}
		$user = $user->toArray();

		$url_del_stt = mkurl(true, 'graph', 'facebook.com', $idStatus, ['access_token' => $user['access_token']]);
		$delstt = json_decode(Curl::to($url_del_stt)->delete(), true);
		if ($delstt === true) {
			return back()->with(['success' => 'Xóa bài viết thành công !', 'del' => true]);
		}
		return back()->with('error', 'Có lỗi xảy ra khi xóa bài viết !');
	}
}
