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

    public function index($p_uid) {
        if (empty($this->account)) {
            return response('Không tìm thấy tài khoản Facebook!', 404);
        }

        $db_comment = FBComment::where('provider_uid', $p_uid)->get();
        return response(explode('|', substr($db_comment->comments, 0, -1)), 200);
    }

    public function show($p_uid, $type) {
        if (empty($this->account)) {
            return response('Không tìm thấy tài khoản Facebook!', 404);
        }

        if ($type !== 'feed' && $type !== 'home') {
            return response('Lỗi!', 404);
        }
        $db_comment = FBComment::where(['provider_uid' => $p_uid, 'type' => $type])->first();
        if (empty($db_comment) || empty($db_comment->comments)) {
            return response('Không có bình luận để xóa!', 404);
        }
        return response(explode('|', substr($db_comment->comments, 0, -1)), 200);
    }

    public function store(Request $request, $p_uid) {
    	if (empty($this->account)) {
    		return response('Không tìm thấy tài khoản Facebook!', 404);
    	}
    	$url = mkurl(true, 'graph.facebook.com', "$request->posts_id/comments", [
    		'message' => $request->comment,
    		'attachment_url' => $request->url_picture,
    		'access_token' => $this->account->access_token
    	]);
    	$data = json_decode(Curl::to($url)->withHeader('User-Agent', agent())->post(), true);
    	if (!empty($data['error'])) {
    		return response($data['error']['message'], 500);
    	}
        if (empty($data['id'])) {
            return response('Không có ID bình luận', 404);
        }

    	$db_comment = FBComment::firstOrCreate([
    		'provider_uid' => $p_uid,
    		'type' => $request->type
    	], [
    		'comments' => $data['id'] . '|'
    	]);
    	if (!$db_comment->wasRecentlyCreated) {
    		$db_comment->comments .= ($data['id'] . '|');
    		$db_comment->save();
    	}
    	return response('Bình luận thành công!', 200);
    }

    public function delete($p_uid, $type, $commented_id) {
        if (empty($this->account)) {
            return response('Không tìm thấy tài khoản Facebook!', 404);
        }
        $url = mkurl(true, 'graph.facebook.com', "$commented_id", [
            'access_token' => $this->account->access_token
        ]);

        $is_success = json_decode(Curl::to($url)->withHeader('User-Agent', agent())->delete(), true);
        if (!empty($is_success['error'])) {
            if ($is_success['error']['code'] !== 200 &&
                ($is_success['error']['code'] !== 100 &&
                    $is_success['error']['code']['error_subcode'] !== 33)) {
                return response('Có lỗi khi xóa bình luận!', 500);
            }
        }

        $db_comment = FBComment::where(['provider_uid' => $p_uid, 'type' => $type])->first();
        $db_comment->comments = preg_replace("/$commented_id\|/m", '', $db_comment->comments);
        $db_comment->save();
        return response('Đã xóa bình luận', 200);
    }
}
