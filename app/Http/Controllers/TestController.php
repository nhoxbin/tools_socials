<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FBComment;

class TestController extends Controller
{
    public function test() {
    	/*$db_comment = FBComment::firstOrNew([
    		'provider_uid' => $p_uid,
    		'type' => 'feed'
    	], [
    		'comments' => $data['id'] . '|'
    	]);
    	if (!$db_comment->wasRecentlyCreated) {
    		$db_comment->comments .= ($data['id'] . '|');
    		$db_comment->save();
    	}*/
    	$db_comment = FBComment::where('provider_uid', '100036593178737')->first();
    	dd($db_comment->toArray());
    }
}
