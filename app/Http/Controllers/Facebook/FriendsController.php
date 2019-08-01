<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Social;
use App\FBAccount;
use Curl;

class FriendsController extends Controller
{
  protected $user_agent;

	public function __construct() {
		$this->user_agent = agent();
	}

	public function getFriendsWithId($id, $access_token) {
		$url = mkurl(true, 'graph.facebook.com', "$id/friends", [
			'fields' => 'name,link,picture.width(30).height(30),mobile_phone,birthday,hometown,location',
			'limit' => '5000',
			'pretty' => 0,
			'access_token' => $access_token
		]);
		$friends = json_decode(Curl::to($url)->get(), true);
		if (empty($friends['data']))
			return 'error';

		$friends_data = $friends['data'];
		$total_count = $friends['summary']['total_count'];

		return [
			'data' => $friends_data,
			'total_count' => $total_count
		];
	}

	public function getList(Request $request, $p_uid) {
		$account = FBAccount::where('user_id', Auth::id())->first();

		$friends = $this->getFriendsWithId($p_uid, $account->access_token);
		if ($friends === 'error') {
			return response(null, 204);
		}
		return response($friends, 200);
	}

	public function unfriend(Request $request, $uid) {
		$account = FBAccount::where('user_id', auth()->id())->first();
		$access_token = $account->access_token;

		$url = mkurl(true, 'graph.facebook.com', "$account[provider_uid]/friends", [
			'uid' => $uid,
			'access_token' => $access_token
		]);
		$status = json_decode(Curl::to($url)->delete(), true);
		if ($status['success']) {
			return response('Hủy kết bạn thành công.', 200);
		}
		return response(null, 204);
	}
}
