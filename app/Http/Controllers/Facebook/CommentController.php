<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FBAccount;
use App\FBComment;
use Curl;

class CommentController extends Controller
{
	private $account;

	public function __construct(Request $request) {
		$p_uid = $request->p_uid;
		if (is_numeric($p_uid)) {
			$this->account = FBAccount::where('provider_uid', $p_uid)->first();
		}
	}

    public function create(Request $request, $p_uid, $id_post) {
    	if (empty($this->account)) {
    		return response('Không tìm thấy tài khoản Facebook!', 404);
    	}

    	$url = mkurl(true, 'graph.facebook.com', "v3.3/$id_post/comments", [
    		'message' => $request->comment,
    		'attachment_url' => $request->url_picture,
    		'method' => 'post',
    		'access_token' => $this->account->access_token
    	]);
    	$data = json_decode(Curl::to($url)->get(), true);
    	if (!empty($data['error'])) {
    		return response('Có lỗi khi bình luận!', 500);
    	}

    	$db_comment = FBComment::firstOrCreate([
    		'provider_uid' => $p_uid
    	], [
    		'comments' => $data['id'] . '|',
    		'type' => 'user'
    	]);
    	if (!$db_comment->wasRecentlyCreated) {
    		$db_comment->comments .= $data['id'] . '|';
    		$db_comment->save();
    	}
    	return response('Bình luận thành công!', 200);
    }
}
