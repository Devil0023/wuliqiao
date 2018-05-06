<?php

namespace App\Http\Middleware;

use Closure;
use Overtrue\Socialite\User as SocialiteUser;


class FakeWechatOauth
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
        $user = new SocialiteUser([
            'id'        => "oiLvNvgskwgsScK7a1culEdKOaUo",
            'name'      => "kanada",
            'nickname' => "kanada",
            'avatar'   => "http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJG7JJuia2EXbfcGJudl89aAAGp49XJWTvia2VFXLtJicfSyCGMia4Wqt9Yciak0hHycNez56SQT9oibZfA/132",
            'email'    => null,
            'original' => [],
            'provider' => 'WeChat',
        ]);

        session(['wechat.oauth_user' => $user]);

        return $next($request);
    }
}
