<?php

namespace App\Http\Controllers;

use App\Models\Pointslog;
use App\Models\Ppointslog;
use App\Models\Vpointslog;
use App\Models\Wxuser;
use App\Models\Pointsrule;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redis;

class WuliqiaoController extends Controller
{

    private $dailymission = 5;

    public function index(){
        $oauth  = session('wechat.oauth_user.default');

        $mission_key  = "Wuliqiao-Mission-".$oauth["id"];
        $mission_info = @Redis::get($mission_key);

        $mission["daily"]     = $this->dailymission;
        $mission["complete"] = count(explode(",", $mission_info));

        $mkey   = "Wuliqiao-Sign-".$oauth["id"];
        $sign   = @Redis::get($mkey);


        $mkey   = "Wuliqiao-Usercenter-Userinfo-".$oauth["id"];
        $json   = @Redis::get($mkey);

        if(empty($json)){
            $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();

            $uinfo   = array(
                "nickname" => $oauth["name"],
                "openid"   => $oauth["openid"],
                "headimgurl" => $oauth["avatar"],

                "points"              => intval($wxuser["points"]),
                "volunteer"           => intval($wxuser["volunteer"]),
                "volunteer_points"   => intval($wxuser["volunteer_points"]),
                "partymember"         => intval($wxuser["partymember"]),
                "partymember_points" => intval($wxuser["partymember_points"]),
            );

            $json = json_encode($uinfo);
            @Redis::setex($mkey, 2, $json);
        }

        $uinfo = json_decode($json, true);

        return view("wechat.index", compact("uinfo", "mission", "sign"));
    }

    public function setting(){
        $oauth  = session('wechat.oauth_user.default');

        $mkey   = "Wuliqiao-Usercenter-Userinfo-Setting-".$oauth["id"];
        $json   = @Redis::get($mkey);
        if(empty($json)) {
            $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();

            $uinfo = array(

                "nickname" => $oauth["name"],
                "openid"   => $oauth["openid"],
                "headimgurl" => $oauth["avatar"],

                "truename" => $wxuser["truename"],
                "mobile" => $wxuser["mobile"],
                "address" => $wxuser["address"],
                "volunteer" => intval($wxuser["volunteer"]),
                "partymember" => intval($wxuser["partymember"]),
            );

            $json = json_encode($uinfo);
            @Redis::setex($mkey, 2, $json);

        }

        $uinfo = json_decode($json, true);

        return view("wechat.setting", compact("uinfo"));
    }

    public function register(){

        $oauth  = session('wechat.oauth_user.default');

        $mkey   = "Wuliqiao-Usercenter-Userinfo-Register-".$oauth["id"];
        $json   = @Redis::get($mkey);
        if(empty($json)) {
            $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();

            $uinfo = array(

                "nickname" => $oauth["name"],
                "openid"   => $oauth["openid"],
                "headimgurl" => $oauth["avatar"],

                "truename" => $wxuser["truename"],
                "mobile" => $wxuser["mobile"],
                "address" => $wxuser["address"],
                "volunteer" => intval($wxuser["volunteer"]),
                "partymember" => intval($wxuser["partymember"]),
            );

            $json = json_encode($uinfo);
            @Redis::setex($mkey, 2, $json);

        }

        $uinfo = json_decode($json, true);

        return view("wechat.register", compact("uinfo"));
    }


    public function smscheck(Request $request){

        $mobile = $request->mobile;

        if(empty($mobile)){
            return array(
                "error_code"    => "400001",
                "error_message" => "参数遗漏",
            );
        }

        $mkey = "Wuliqiao-SMSCheck-".$mobile;

        if(@Redis::get($mkey)){
            return array(
                "error_code"    => "400006",
                "error_message" => "短信请求太频繁",
            );
        }

        $code = $this->createCode(6);
        @Redis::setex($mkey, 60, $code);
        session(['smscheck' => $code]);

        return array(
            "error_code" => "0",
            "error_message" => "success",
            "code" => $code,
        );

    }


    public function updateinfo(Request $request){

        $mobile      = $request->mobile;
        $truename    = $request->truename;
        $address     = $request->address;
        $code        = $request->code;
        $volunteer   = intval($request->volunteer);
        $partymember = intval($request->partymember);

        if(empty($mobile) || empty($truename) || empty($address) || empty($code)){
            return array(
                "error_code"    => "400001",
                "error_message" => "参数遗漏",
            );
        }

        if(session("smscheck") != $code){
            return array(
                "error_code"    => "400007",
                "error_message" => "验证码错误",
            );
        }


        $oauth  = session('wechat.oauth_user.default');
        $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();

        $first  = false;

        if(empty($wxuser->truename) || is_null($wxuser->truename)){
            $first = true;
        }

        $result = $wxuser->update(array(
            "truename" => $truename,
            "address"  => $address,
            "mobile"   => $mobile,
            "volunteer" => $volunteer,
            "partymember" => $partymember,
        ));

        if($result){

            if($first){
                Pointsrule::addPointsByRule(1, $wxuser->id);
            }

            $request->session()->forget("smscheck");

            return array(
                "error_code"    => "0",
                "error_message" => "success",
            );
            
        }else{
            return array(
                "error_code"    => "400002",
                "error_message" => "修改个人信息失败",
            );
         }


    }

    public function pointslog(){
        $oauth  = session('wechat.oauth_user.default');
        $mkey   = "Wuliqiao-Pointslog-".$oauth["id"];
        $json   = @Redis::get($mkey);

        if(empty($json)){
            $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();
            $list   = Pointslog::where("uid", $wxuser->id)->orderBy("created_at", "desc")->take(10)->get()->toArray();

            $uinfo   = array(
                "nickname"    => $oauth["name"],
                "openid"      => $oauth["openid"],
                "headimgurl" => $oauth["avatar"],

                "points"      => intval($wxuser["points"]),
                "list"        => $list,

            );

            @Redis::setex($mkey, 10, json_encode($uinfo));
        }

        return view("wechat.pointslog", compact("uinfo"));
    }

    public function ppointslog(){
        $oauth  = session('wechat.oauth_user.default');
        $mkey   = "Wuliqiao-PPointslog-".$oauth["id"];
        $json   = @Redis::get($mkey);

        if(empty($json)){
            $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();
            $list   = Ppointslog::where("uid", $wxuser->id)->orderBy("created_at", "desc")->take(10)->get()->toArray();

            $uinfo   = array(
                "nickname"    => $oauth["name"],
                "openid"      => $oauth["openid"],
                "headimgurl" => $oauth["avatar"],

                "partymember_points"  => intval($wxuser["partymember_points"]),
                "list"        => $list,

            );

            @Redis::setex($mkey, 10, json_encode($uinfo));
        }

        return view("wechat.ppointslog", compact("uinfo"));
    }

    public function vpointslog(){
        $oauth  = session('wechat.oauth_user.default');
        $mkey   = "Wuliqiao-VPointslog-".$oauth["id"];
        $json   = @Redis::get($mkey);

        if(empty($json)){
            $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();
            $list   = Vpointslog::where("uid", $wxuser->id)->orderBy("created_at", "desc")->take(10)->get()->toArray();

            $uinfo   = array(
                "nickname"    => $oauth["name"],
                "openid"      => $oauth["openid"],
                "headimgurl" => $oauth["avatar"],

                "volunteer_points"  => intval($wxuser["volunteer_points"]),
                "list"        => $list,

            );

            @Redis::setex($mkey, 10, json_encode($uinfo));
        }

        return view("wechat.vpointslog", compact("uinfo"));
    }

    public function sign(){
        $oauth  = session('wechat.oauth_user.default');

        $mkey   = "Wuliqiao-Sign-".$oauth["id"];
        $sign   = @Redis::get($mkey);

        if($sign){
            return array(
                "error_code"    => "400008",
                "error_message" => "今日已签到",
            );
        }

        $expire = strtotime(date("Y-m-d")." 23:59:59") - time();

        $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();

        if(Pointsrule::addPointsByRule(2, $wxuser->id)){

            @Redis::setex($mkey, $expire, 1);

            return array(
                "error_code"    => "0",
                "error_message" => "success",
                "points"         => Wxuser::find($wxuser->id)->points,
            );

        }else{
            return array(
                "error_code"    => "400009",
                "error_message" => "签到失败",
            );
        }

    }

    private function createCode($length = 6){

        $code = "";
        for($i = 0; $i < $length; $i++){
            $code .= rand(0, 9);
        }

        return $code;
    }

}
