<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FBAccount;
use App\User;
use Curl;

class AccountController extends Controller
{
    public function index() {
        $accounts = FBAccount::all();
        return response($accounts, 200);
    }

    public function show() {
        $account = FBAccount::where('user_id', auth()->id())
            ->select('id', 'provider_uid', 'name', 'is_active', 'status', 'user_id')
            ->get();
            
        return response($account, 200);
    }

    public function update() {
        $accounts = User::find(auth()->id())->facebook;
        foreach ($accounts as $account) {
            // get name and id of user
            $url = mkurl(true, 'graph.facebook.com', "v3.3/$account[provider_uid]", [
                'access_token' => $account->access_token
            ]);
            $userInfo = json_decode(Curl::to($url)->get(), true);

            if (!isset($userInfo['id']) || !isset($userInfo['name'])) {
                $account->is_active = 0;
                $account->status = 'Không lấy được thông tin tài khoản. Vui lòng đăng nhập lại!';
                $account->access_token = null;
                $account->cookie = null;
            } else {
                $account->name = $userInfo['name'];
            }
            $account->save();
        }

        return response('Cập nhật thành công!', 200);
    }

    public function login(Request $request) {
        $user_id = auth()->id();

        $username = $request->username;
        $password = $request->password;
        $data_user = json_decode(sign_creator($username, $password), true);
        if ($err_msg = HandleLogin($data_user)) {
            return response($err_msg, 422);
        }
        
        // $data_user trả về kết quả và lấy thông tin người dùng
        $p_uid = $data_user['session_cookies'][0]['value'];
        $access_token = $data_user['access_token'];
        $cookie = convert_cookie($data_user['session_cookies']);
        
        $account = FBAccount::where('provider_uid', $p_uid)->first();
        // cả user và account đều có trên hệ thống
        if ($account !== null) {
            // user_id trên hệ thống khác user_id vừa đăng nhập account
            if ($user_id != $account->user_id) {
                $message = 'Tài khoản Facebook này được đăng nhập ở nơi khác.';
                return response($message, 422);
            }
        }

        // Curl get name
        $url = mkurl(true, 'graph.facebook.com', "v3.3/$p_uid", ['access_token' => $access_token]);
        $user = json_decode(Curl::to($url)->get(), true);
        $info = FBAccount::updateOrCreate([
            'user_id' => $user_id,
            'provider_uid' => $p_uid
        ], [
            'name' => $user['name'],
            'is_active' => 1,
            'status' => null,
            'access_token' => $access_token,
            'cookie' => $cookie,
        ]);
        $status = 'Cập nhật';
        if ($info->wasRecentlyCreated) {
            $status = 'Đăng nhập';
        }
        return response("$status Facebook thành công!", 200);
    }
}
