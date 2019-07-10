<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckHasAccountFB
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
        $user = User::with('facebook')->where('id', auth()->id())->first();
        if (!empty($user['facebook'])) {
            return $next($request);
        }
        return response('Bạn chưa có tài khoản Facebook', 403);
    }
}
