<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FBAccount;
use App\FBComment;
use Curl;

class HomeController extends Controller
{
    private $account;

    public function __construct(Request $request) {
        // ktra $uid gửi từ client, nếu hợp lệ thì lấy tài khoản fb tương ứng
        $uid = $request->uid;
        if (!empty($uid)) {
            $this->account = FBAccount::where('provider_uid', $uid)->first();
        }
    }

    private function getPosts($account, $limitCommentPosts) {
        // lấy bài viết
    	$url = mkurl(true, 'graph.facebook.com', 'v3.3/me/home', [
    		'fields' => 'from',
    		'limit' => 200,
    		'access_token' => $account->access_token
    	]);
    	$posts = json_decode(Curl::to($url)->get(), true);
        if (isset($posts['error'])) {
            return false;
        }
        // lấy ra id của người đăng bài và CURL để xem là page hay user
        $posts_data = $posts['data'];
        $str_ids = preg_replace('/_.+?,/m', ',', implode(',', array_column($posts_data, 'id')));

        // lấy kiểu trả về của 1 id (user hoặc page)
        $url = mkurl(true, 'graph.facebook.com', '', [
            'ids' => $str_ids,
            'fields' => 'metadata.fields(type)',
            'metadata' => 1,
            'access_token' => $account->access_token
        ]);
        $typeof_ids = json_decode(Curl::to($url)->get(), true);
        // lọc kiểu cần lấy (page hoặc user)
        $typeof_ = array_filter($typeof_ids, function($item) {
            // lọc và trả về các id có type là page
            if ($item['metadata']['type'] === 'page') {
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

        // quản lý comment
        $commented = FBComment::where('facebook_account_id', $account->id)->first();
        if ($commented !== null && !empty($commented->post_ids)) {
            // lấy những bài viết đã comment trong db
            $postsHasCommented = explode('|', $commented->post_ids);
            $count_postsHasCommented = count($postsHasCommented);
            // lấy id bài viết từ $posts mới lấy dữ liệu
            $posts_id = array_column($posts, 'id');

            // nếu 1 id trong db ko có trong $posts thì id đó đã cũ và xóa id đó trong db
            foreach ($postsHasCommented as $key => $value) {
                if (!in_array($value, $posts_id)) {
                    unset($postsHasCommented[$key]);
                }
            }
            // sau unset ở trên, đếm lại và lưu
            if ($count_postsHasCommented > count($postsHasCommented)) {
                $commented->post_ids = implode('|', $postsHasCommented);
                $commented->save();
            }

            // bài viết đã comment tồn tại trong $posts thì ko lấy bài viết đó
            foreach ($posts as $key => $value) {
                if (in_array($value['id'], $postsHasCommented)) {
                    unset($posts[$key]);
                }
            }
        }
        return array_slice($posts, 0, $limitCommentPosts);
    }

    private function comment($account, $id_post, $picture, $comment) {
        $url = mkurl(true, 'graph.facebook.com', "$id_post/comments", [
            'message' => $comment,
            'attachment_url' => $picture,
            'method' => 'post',
            'access_token' => $account->access_token
        ]);
        $status_comment = json_decode(Curl::to($url)->get(), true);
        if (!empty($status_comment['error'])) {
            return false;
        }
        $comment = FBComment::where('facebook_account_id', $account->id)->first();
        if ($comment === null) {
            $comment = new FBComment;
            $comment->facebook_account_id = $account->id;
            $comment->post_ids = $id_post . '|';
            $comment->comment_ids = $status_comment['id'] . '|';
            $comment->save();
        } else {
            $comment->post_ids .= ($id_post . '|');
            $comment->comment_ids .= ($status_comment['id'] . '|');
            $comment->save();
        }
        return true;
    }

    public function deleteComment(Request $request) {
        if ($this->account === null) {
            return response('Không tìm thấy tài khoản Facebook!', 404);
        }

        $comment = FBComment::where('facebook_account_id', $this->account->id)->first();
        if ($request->isMethod('GET')) {
            return response(explode('|', substr($comment->comment_ids, 0, -1)), 200);
        } else {
            $commented_id = $request->commented_id;
            $url = mkurl(true, 'graph.facebook.com', $commented_id, [
                'method' => 'delete',
                'access_token' => $this->account->access_token
            ]);
            // dữ liệu trả về là true nếu xóa được
            $is_success = Curl::to($url)->get();
            if ($is_success !== 'true') {
                return response('Có lỗi khi xóa bình luận!', 500);
            }
            $comment->comment_ids = preg_replace("/$commented_id\|/m", '', $comment->comment_ids);
            $comment->save();
            return response('Đã xóa bình luận', 200);
        }
    }

    public function startComment(Request $request) {
        if ($this->account === null) {
            return response('Không tìm thấy tài khoản Facebook!', 404);
        }

        // ktra nếu có id post thì comment, nếu ko thì lấy bài viết
        if (empty($request->id_post)) {
            $limitCommentPosts = $request->limitCommentPosts;
            if (!is_numeric($limitCommentPosts) || $limitCommentPosts < 10 || $limitCommentPosts > 200) {
                return response('Bạn ko được lấy nhỏ hơn 10 và quá 200 bài viết!', 422);
            }

            // lấy bài viết ở home (newfeed)
            $posts = $this->getPosts($this->account, $limitCommentPosts);
            if ($posts === false) {
                return response('Lỗi khi lấy dữ liệu, cập nhật lại tài khoản Facebook!', 500);
            }
            return $posts;
        } else {
            if (trim($request->comment) === '') {
                return response('Vui lòng nhập nội dung muốn bình luận!', 422);
            }
            $is_success = $this->comment($this->account, $request->id_post, $request->url_picture, $request->comment);
            if ($is_success === false) {
                return response('Không bình luận được', 500);
            } else {
                return response('Đã bình luận', 200);
            }
        }
    }
}
