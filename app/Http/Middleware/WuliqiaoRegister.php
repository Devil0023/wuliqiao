<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Wxuser;

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
        //$userinfo = session('wechat.oauth_user'); // 拿到授权用户资料
        $userinfo = session('wechat.oauth_user.default'); // 测试

        print_r($wxuser["id"]);

        $wxuser = Wxuser::where("openid", "=", $userinfo["id"]);


        die;

        return $next($request);
    }
}
