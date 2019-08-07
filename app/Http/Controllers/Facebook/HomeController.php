<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FBAccount;
use App\FBComment;
use Curl;

class HomeController extends Controller
{
    public function getPosts(Request $request, $p_uid, $type, $limit) {
        if (!is_numeric($limit)) {
            return response('Sai định dạng giới hạn bài viết!', 422);
        }
        // lấy bài viết
    	$url = mkurl(true, 'graph.facebook.com', 'me/home', [
    		'fields' => 'from',
    		'limit' => 100,
    		'access_token' => $request->account['access_token']
    	]);
    	$posts = json_decode(Curl::to($url)->get(), true);
        if (isset($posts['error'])) {
            return response('Lỗi không lấy được bài viết ở newfeed!', 422);
        }
        // lấy ra id của người đăng bài và CURL để xem là page hay user
        $posts_data = $posts['data'];
        $str_ids = preg_replace('/_.+?,/m', ',', implode(',', array_column($posts_data, 'id')));

        // lấy kiểu trả về của 1 id (user hoặc page)
        $url = mkurl(true, 'graph.facebook.com', '', [
            'ids' => $str_ids,
            'fields' => 'metadata.fields(type)',
            'metadata' => 1,
            'access_token' => $request->account['access_token']
        ]);
        $typeof_ids = json_decode(Curl::to($url)->get(), true);
        // lọc kiểu cần lấy (page hoặc user)
        $typeof_ = array_filter($typeof_ids, function($item) use ($type) {
            // lọc và trả về các id có type là page
            if ($item['metadata']['type'] === $type) {
                return true;
            }
        });
        // lấy id của page hoặc user
        $_ids = array_keys($typeof_);

        // $posts chứa các bài viết của page hoặc user
        $posts = [];
        foreach ($posts_data as $item) {
            if (in_array($item['from']['id'], $_ids)) {
                array_push($posts, $item);
            }
        }
        return array_slice(array_column($posts, 'id'), 0, $limit);
    }
}
