<?php

namespace App\Http\Middleware;

use Closure;

class WuliqiaoRegister
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

        $user = session('wechat.oauth_user.default'); // 拿到授权用户资料
        dd($user);



        return $next($request);
    }
}
