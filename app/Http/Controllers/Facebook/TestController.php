<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function test(Request $request, $p_uid) {
        echo $request->account['access_token'];
    }
}
