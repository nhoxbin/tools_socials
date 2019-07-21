<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FBAccount;
use Curl;

class MultiThreadsController extends Controller
{
	private $account;

	public function __construct(Request $request) {
		if (is_numeric($request->p_uid)) {
			$this->account = FBAccount::where('provider_uid', $request->p_uid)->first();
		}
	}

    public function getMultiPostsOfMultiUser($p_uid, $uids, $limit) {
    	if (empty($this->account)) {
			return response('Không tìm thấy tài khoản Facebook!', 404);
		}
		if ($limit < 10 || $limit > 200) {
			return response('Lấy từ 10 > 200 bài viết!', 500);
		}

    	$url = mkurl(true, 'graph.facebook.com', 'v3.3', [
    		'ids' => $uids,
    		'fields' => "posts.limit($limit){id}",
			'access_token' => $this->account->access_token
		]);
    	$data_multi_user = json_decode(Curl::to($url)->get(), true);
    	if (!empty($data_multi_user['error'])) {
    		return response('Có lỗi khi lấy bài viết, kiểm tra lại ID!', 500);
    	}

    	$multi_posts = [];
    	foreach ($data_multi_user as $id => $value) {
    		$postsOfMultiUser[$id] = array_column($value['posts']['data'], 'id');
    		foreach ($postsOfMultiUser[$id] as $value) {
    			array_push($multi_posts, $value);
    		}
    	}

    	return $multi_posts;
    }
}
