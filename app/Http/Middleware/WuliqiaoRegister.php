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
        $userinfo = session('wechat.oauth_user'); // 拿到授权用户资料
        $original = $userinfo["default"]->original;

	//print_r($original); die;

        $wxuser   = Wxuser::where("openid", "=", $original["openid"])->first();


        //$userinfo = session('wechat.oauth_user.default'); // 测试
        //$wxuser   = Wxuser::where("openid", "=", $userinfo["id"])->first();

        if(is_null($wxuser)){
            $newuser = new Wxuser();
            $newuser->openid     = $original["openid"];
            $newuser->nickname   = $original["nickname"];
            $newuser->sex         = $original["sex"];
            $newuser->language   = $original["language"];
            $newuser->province   = $original["province"];
            $newuser->city        = $original["city"];
            $newuser->country    = $original["country"];
            $newuser->headimgurl = $original["headimgurl"];
            $newuser->privilege  = json_encode($original["privilege"]);
            $newuser->unionid    = isset($original["unionid"])? $original["unionid"]: "";

//            $newuser->openid     = $userinfo["id"];
//            $newuser->nickname   = $userinfo["name"];
//            $newuser->headimgurl = $userinfo["avatar"];
            $newuser->save();

        }

        return $next($request);
    }
}
