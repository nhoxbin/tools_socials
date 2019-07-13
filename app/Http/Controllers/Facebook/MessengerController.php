<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Facebook\FriendsController as Friends;
use App\FBAccount;
use Curl, Validator;

class MessengerController extends Controller
{
    protected $data_message = [];

	private function rank($url, array $rank) {
		$conversations = json_decode(Curl::to($url)->get(), true);
		if (isset($conversations['error'])) {
			return false;
		}
		$conversations_data = $conversations['data'];
		foreach ($conversations_data as $item) {
			if (count($item['participants']['data']) == 2) {
				$item['participants'] = $item['participants']['data'];
				array_push($rank, $item);
			}
		}
		if (!isset($conversations['paging']['next'])) {
			return $rank;
		}
		return $this->rank($conversations['paging']['next'], $rank);
	}

	public function kount() {
		$account = FBAccount::where('user_id', auth()->id())->first();
		$url = mkurl(true, 'graph.facebook.com', "$account[provider_uid]/conversations", [
			'fields' => 'message_count,link,participants',
			'limit' => '5000',
			'access_token' => $account['access_token']
		]);
		$rank = $this->rank($url, []);
		if ($rank == false) {
			return response(null, 204);
		}
		$rank = arr_sort($rank, 'message_count', SORT_DESC);
		array_splice($rank, 15);
		
		return response($rank, 200);
	}

	public function inboxes(Request $request, Friends $friends) {
		if ($request->isMethod('get')) {
			return view('auto.messenger.inboxes');
		} elseif ($request->isMethod('post')) {
			if (!isset($request->user) && !isset($request->friend_id)) {
				// check id facebook hợp lệ hay ko
				$idToGetListFriends = $request->idToGetListFriends;
				if (!is_numeric(trim($idToGetListFriends))) {
					$message = ['message' => 'ID Facebook không đúng!'];
					return response()->json(BinJSON('alert', $message));
				}

				// Lấy account fb từ db
				$user = Social::where('user_id', Auth::id())->first();
				if ($user === null) {
					return response()->json(BinJSON(
						['alert-redirect', 'home'],
						['message' => 'User ko có trên hệ thống!']
					));
				}

				// get list friends từ FriendsController method getFriendsWithId($id, $access_token)
				$friends = $friends->getFriendsWithId($idToGetListFriends, $user->access_token);
				// check lỗi nếu không lấy được danh sách friends
				if (isset($friends['error'])) {
					return response()->json(BinJSON('alert',
						['message' => $friends['error']]
					));
				}

				// curl success và lấy danh sách friends
				$friends = $friends['friends_data'];

				// get fb_dtsg to send tin nhắn
				$url = 'https://mbasic.facebook.com/messages/compose?ids=' . $friends[0]['id'];
				$get_fb_dtsg = Curl::to($url)
					->withHeaders([
						'cookie: '.$user->cookie,
						'user-agent: '.agent()
					])->get();

				$user->fb_dtsg = null;
				if(preg_match('/<.*name="fb_dtsg" value="(.+?)".*>/', $get_fb_dtsg, $fb_dtsg))
					$user->fb_dtsg = $fb_dtsg[1];
				// ./ end get fb_dtsg

				return response()->json(BinJSON('success', [
					'friends' => $friends,
					'user' => $user
				]));
			} else {
				$user = json_decode($request->user);
				$friend_id = $request->friend_id;

				// url to send messages
				$url_send = 'https://mbasic.facebook.com/messages/send/?icm=1';
				$files = $request->file();
				if (count($files) > 0) {
					// Validate if send with file
					$validate = [];
					foreach ($files as $name => $picture) {
						$validate[$name] = 'image|mimes:jpeg,jpg,png,gif|max:2048';
					}

					$validation = Validator::make($request->all(), $validate);

					if (!$validation->passes()) {
						return response()->json(BinJSON('error-inject',
							['message' => $validation->errors()->all()]
						));
					}

					// url send messages with file img
					$url_send = 'https://upload.facebook.com/_mupload_/mbasic/messages/attachment/photo/';
				}

				// Curl to execute inbox
				$rq_send = Curl::to($url_send)
					->withHeaders([
			        	'Cookie: ' . $user->cookie,
						'user-agent: ' . agent(),
			        	'Cache-Control: no-cache'
			        ])
					->withData([
						'fb_dtsg' => $user->fb_dtsg,
						// 'charset_test' => '€,´,€,´,水,Д,Є',
						'ids' => $friends_id,
						'body' => $request->messages
					])
					->withOption('FOLLOWLOCATION', true);

				if (count($files) > 0) {
					$rq_send = $rq_send->withContentType('multipart/form-data');

					foreach ($files as $name => $picture) {
						$rq_send = $rq_send->withFile($name, realpath($picture));
					}
				} else {
					$rq_send = $rq_send->withContentType('application/x-www-form-urlencoded');
				}

				$rq_send = $rq_send->post();

				// values to return
				$type = 'error-inject';
				$data = [
					'id' => $friends_id,
					'fb_dtsg' => $user->fb_dtsg,
					'message' => 'Không gửi được tin nhắn!'
				];

				// request ok và lấy được data fb_dtsg
				if (preg_match('/<.*name="fb_dtsg" value="(.+?)".*>/', $rq_send, $fb_dtsg)) {
					$data['fb_dtsg'] = $fb_dtsg[1];

					if (preg_match('/id="fua"/', $rq_send)) {
						// send success
						$type = 'success-inject';
						$data['message'] = 'Gửi tin nhắn thành công!';
					}
				}
				return response()->json(BinJSON($type, $data));
			}
		} else {
			return;
		}
	}
}
