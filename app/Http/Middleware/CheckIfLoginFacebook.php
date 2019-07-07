<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Account;

class CheckIfLoginFacebook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // khi login facebook kiểm tra nếu đã có tk và đang hoạt động thì ko cho đăng nhập
        $account = Account::where('user_id', auth()->id())->first();
        if ($account !== null) {
            if ($account->is_active === 1) {
                return response('Tài khoản Facebook đã có sẵn! Vui lòng ấn cập nhật để xem trạng thái!', 422);
            }
        }
        return $next($request);
    }
}
