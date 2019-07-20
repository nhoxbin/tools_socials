<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test() {
    	$db_comment = FBComment::where('provider_uid', '100036593178737');
    }
}
