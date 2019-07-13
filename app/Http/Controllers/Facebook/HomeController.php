<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FBAccount;
use App\FBComment;
use Curl;

class HomeController extends Controller
{
    private function getPosts($account, $since, $until, $limitPosts) {
        // lấy bài viết
    	$url = mkurl(true, 'graph.facebook.com', 'me/home', [
    		'fields' => 'from{name},created_time',
    		'limit' => '300',
    		'access_token' => $account->access_token
    	]);
    	$posts = json_decode(Curl::to($url)->get(), true);
        if (isset($posts['error'])) {
            return false;
        }
        // lấy ra id của bài viết để xem là page hay user
        $posts_data = $posts['data'];
        $uids = implode(',', array_column($posts_data, 'id'));
        $uids = preg_replace('/_.+?,/m', ',', $uids);

        // lấy kiểu trả về của 1 id (user hoặc page)
        $url = mkurl(true, 'graph.facebook.com', '', [
            'ids' => $uids,
            'fields' => 'metadata.fields(type)',
            'metadata' => 1,
            'access_token' => $account->access_token
        ]);
        $typeof_uids = json_decode(Curl::to($url)->get(), true);
        // lọc kiểu cần lấy (page hoặc user)
        $typeof_pages = array_filter($typeof_uids, function($items) {
            // lọc và trả về các id có type là page
            if ($items['metadata']['type'] === 'page') {
                return true;
            }
        });
        $page_ids = array_keys($typeof_pages);

        $posts = [];
        foreach ($posts_data as $post) {
            if (in_array($post['from']['id'], $page_ids)) {
                array_push($posts, $post);
            }
        }

        // lấy những bài viết đã comment từ db và lọc nếu bài viết vừa lấy từ home
        // có trong những bài đã comment thì loại bỏ, nếu ko có thì loại bỏ id đã comment ở db
        $has_commented = FBComment::where('facebook_account_id', $account->id)->first();
        if ($has_commented !== null) {
            // quản lý comment
            $posts_commented = explode('|', $has_commented->posts);
            $posts_id = array_column($posts, 'id');
            // lặp qua các bài viết đã comment và xem nếu ko có trong bài viết vừa get thì loại bỏ
            // id đó trong db và khi nào id đó ($posts_commented) ko tìm bài viết trong $posts thì mới xóa đi
            foreach ($posts_commented as $key => $post_commented) {
                if (!in_array($post_commented, $posts_id)) {
                    unset($posts_commented[$key]);
                }
            }
            // đếm tổng số lượng id bài viết trong db lớn hơn số lượng bài viết vừa qua vòng lặp ở trên
            // thì lưu lại số lượng bài viết vừa lọc vào db
            if (count(explode('|', $has_commented->posts)) > count($posts_commented)) {
                $has_commented->posts = implode('|', $posts_commented);
                $has_commented->save();
            }

            foreach ($posts as $key => $value) {
                if (in_array($value['id'], $posts_commented)) {
                    unset($posts[$key]);
                }
            }
            return $posts;
        } else {
        	return $posts;
        }
    }

    public function comment($account, $id_post, $comment) {
        $url = mkurl(true, 'graph.facebook.com', "$id_post/comments", [
            'message' => $comment,
            'method' => 'post',
            'access_token' => $account->access_token
        ]);
        $status_comment = json_decode(Curl::to($url)->get(), true);
        if (!empty($status_comment['error'])) {
            return false;
        }
        return $status['id'];
    }

    public function startComment(Request $request) {
        // ktra $uid gửi từ client, nếu hợp lệ thì lấy tài khoản fb tương ứng
        $uid = $request->uid;
        if (empty($uid)) {
            return response('Không tìm thấy ID Facebook!', 404);
        }
        $account = new FBAccount;
        if ($uid !== 'random') {
            if (!is_numeric($uid)) {
                return response('ID ko đúng!', 422);
            } else {
                $account = $account->where('provider_uid', $uid)->first();
            }
        } else {
            $account = $account->where('user_id', auth()->id())->get();
            $account = $account[rand(0, count($account)-1)];
        }

        // lấy account facebook và ktra nếu có id post thì comment
        if (!empty($request->id_post)) {
            $is_success = $this->comment($account, $request->id_post, $request->comment);
            if ($is_success === false) {
                return response('Không bình luận được, cập nhật lại tài khoản Facebook', 500);
            } else {
                return response('Đã bình luận.', 200);
            }
        } else {
            $limitPosts = $request->limitPosts;
            $date_range = $request->date_range;
            // +13 tiếng để lấy bài viết tốt hơn và đúng với giờ VN
            $since = strtotime($date_range[0]) + 13 * 3600;
            $until = strtotime($date_range[1]) + 13 * 3600;
            // lấy bài viết ở home (newfeed)
        	$posts = $this->getPosts($account, $since, $until, $limitPosts);
            if ($posts === false) {
                return response('Lỗi khi lấy dữ liệu, cập nhật lại tài khoản Facebook!', 500);
            }
            return $posts;
        }
    }
}
