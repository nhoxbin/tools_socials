<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FBComment;
use App\User;

class TestController extends Controller
{
    public function test() {
        // chạy cái này
    	$account = User::find(auth()->id());
        $account->role_id = 4;
    }
}
