<?php

namespace App\Http\Middleware;

use Closure;
use App\Account;

class CheckIsLoginFacebook
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
        $account = Account::where('user_id', auth()->id())->first();
        if ($account === null || $account->is_active === 0) {
            return response(null, 204);
        }
        return $next($request);
    }
}
