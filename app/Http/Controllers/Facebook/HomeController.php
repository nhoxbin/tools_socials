<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Account;

class HomeController extends Controller
{
    public function getHomePosts($homeLimitPosts) {
    	if ($homeLimitPosts > 150) {
    		return response("Bạn ko được bình luận quá $homeLimitPosts bài viết", 422);
    	}
    	$account = Account::where('user_id', auth()->id());
    	$url = mkurl(true, 'graph.facebook.com', "$account[provider_uid]/home", [
    		'fields' => 'from{name}',
    		'limit' => $homeLimitPosts,
    		'access_token' => $account->access_token
    	]);
    	$posts = json_decode(Curl::to($url)->get(), true);
    	return $posts;
    }

    public function autoComment(Request $request) {
    	$this->getHomePosts($request->homeLimitPosts);
    }
}
