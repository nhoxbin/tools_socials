<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Account;
use Curl;

class GroupsController extends Controller
{
    public function getList() {
    	$account = Account::where('user_id', auth()->id())->first();
    	$url = mkurl(true, 'graph.facebook.com', "$account[provider_uid]/groups", [
    		'fields' => 'name,member_count,privacy,administrator',
    		'limit' => '500',
    		'access_token' => $account->access_token
    	]);
    	$groups_data = json_decode(Curl::to($url)->get(), true);
    	return response($groups_data['data'], 200);
    }
}
