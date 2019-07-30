<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;

class CheckIsMember
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
        $user = User::with('role', 'facebook')->where('id', auth()->id())->first()->toArray();
        // nếu member đã đăng nhập 1 nick fb, ko cho đăng nhập nữa
        if (!empty($user['facebook']) && $user['role']['name'] === 'Member') {
            return response('Bạn hãy nâng cấp VIP', 403);
        }
        return $next($request);
    }
}
