<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FBAccount;
use App\FBComment;
use Curl;

class CommentController extends Controller
{
    public function index($p_uid) {
        $db_comment = FBComment::where('provider_uid', $p_uid)->get();
        return response(explode('|', substr($db_comment->comments, 0, -1)), 200);
    }

    public function show($p_uid, $type) {
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
    	$url = mkurl(true, 'graph.facebook.com', "$request->posts_id/comments", [
    		'message' => $request->comment,
    		'attachment_url' => $request->url_picture,
    		'access_token' => $request->account['access_token']
    	]);
    	$data = json_decode(Curl::to($url)->withHeader('User-Agent', agent())->post(), true);
    	if (!empty($data['error'])) {
    		return response($data['error']['message'], 500);
    	}
        if (empty($data['id'])) {
            return response('Đã bình luận nhưng không có ID!', 404);
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

    public function delete(Request $request, $p_uid, $type, $commented_id) {
        $url = mkurl(true, 'graph.facebook.com', "$commented_id", [
            'access_token' => $request->account['access_token']
        ]);

        $is_success = json_decode(Curl::to($url)->withHeader('User-Agent', agent())->delete(), true);
        if (!empty($is_success['error'])) {
            $err_code = $is_success['error']['code'];
            if ($err_code !== 200 && ($err_code !== 100 && $err_code['error_subcode'] !== 33)) {
                return response('Có lỗi khi xóa bình luận!', 500);
            }
        }

        $db_comment = FBComment::where(['provider_uid' => $p_uid, 'type' => $type])->first();
        $db_comment->comments = preg_replace("/$commented_id\|/m", '', $db_comment->comments);
        $db_comment->save();
        return response('Đã xóa bình luận', 200);
    }
}
