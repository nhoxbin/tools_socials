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
        // khi login facebook kiểm tra nếu đã có tk và đang hoạt động thì ko cho đăng nhập
        $user = User::with('role', 'facebook')->where('id', auth()->id())->first()->toArray();
        if ($user['role']['name'] === 'Member' &&
            !empty($user['facebook']) &&
            $user['facebook'][0]['is_active'] === 1
        ) {
            return response('Bạn hãy nâng cấp VIP', 403);
        }
        return $next($request);
    }
}
