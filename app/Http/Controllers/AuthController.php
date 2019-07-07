<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Register;
use Carbon\Carbon;
use App\User;
use DB;

class AuthController extends Controller
{
    public function login(Request $request) {
		if ($token = auth()->attempt($request->only('email', 'password'))) {
			return response('Đăng nhập thành công!', 200)->header('Authorization', $token);
		}
		return response('Sai tài khoản hoặc mật khẩu!', 422);
    }

    public function register(Register $request) {
        $input = $request->only('username', 'email', 'password');
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $message = 'Lỗi khi đăng kí!';
        // if create user success then create a token from $input
        if ($user->wasRecentlyCreated) {
            $message = 'Đăng kí thành công.';
            $code = 201; // created
        }
        return response($message, $code==201 ? $code : 422); // unprocessable entity
    }

    public function logout(Request $request) {
        auth()->invalidate();
        return response('Logged out Successfully.', 200);
    }

    public function user(Request $request) {
    	$user = User::find(auth()->id());
        return response($user, 200);
    }

    public function refresh() {
        try {
            $token = auth()->refresh();
            return response('success', 200)
                ->header('Authorization', $token);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response([
                'errors' => $e->getMessage(),
                'message' => 'Refresh Token Error'
            ], 401);
        }
    }
}
