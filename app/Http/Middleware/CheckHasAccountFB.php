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
        $user = User::find(auth()->id())->facebook->toArray();
        if (!empty($user)) {
            $aProvider_uid = array_column($user, 'provider_uid');
            if (($pos = array_search($request->p_uid, $aProvider_uid)) !== false) {
                $request->request->add(['account' => $user[$pos]]);
                return $next($request);
            }
            return response('Không tìm thấy tài khoản Facebook', 500);
        }
        return response('Bạn chưa có tài khoản Facebook', 403);
    }
}
