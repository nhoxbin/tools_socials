<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FBAccount;

class PostsController extends Controller
{
	private $account;

	public function __construct(Request $request) {
		$this->account = FBAccount::where('provider_uid', $request->p_uid)->first();
	}

    public function interact($posts_id, $limit) {
    	if (empty($this->account)) {
			return response('Không tìm thấy tài khoản Facebook!', 404);
		}
		if ($limit > 30) {
			return response('Lấy ít hơn 30 người!', 500);
		}

    	$url = mkurl(true, 'graph.facebook.com', "v2.2/$posts_id/reactions", [
			'limit' => $limit,
			'access_token' => $account->access_token
		]);
    }
}
