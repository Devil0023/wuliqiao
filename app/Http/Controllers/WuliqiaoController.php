<?php

namespace App\Http\Controllers;

use App\Models\Wxuser;
use Illuminate\Http\Request;

class WuliqiaoController extends Controller
{

    public function index(){
        $oauth  = session('wechat.oauth_user.default');

        $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();

        $info   = array(
            "nickname" => $oauth["name"],
            "openid"   => $oauth["openid"],
            "headimgurl" => $oauth["avatar"],

            "points"              => intval($wxuser["points"]),
            "volunteer"           => intval($wxuser["volunteer"]),
            "volunteer_points"   => intval($wxuser["volunteer_points"]),
            "partymember"         => intval($wxuser["partymember"]),
            "partymember_points" => intval($wxuser["partymember_points"]),
        );

        return view("wechat.index", compact("info"));
    }

    public function setting(){
        $oauth  = session('wechat.oauth_user.default');

        $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();

        $info   = array(
            "truename"    => $wxuser["truename"],
            "mobile"      => $wxuser["mobile"],
            "address"     => $wxuser["address"],
            "volunteer"   => intval($wxuser["volunteer"]),
            "partymember" => intval($wxuser["partymember"]),
        );

        return view("wechat.setting", compact("info"));
    }

    public function register(){
        return view("wechat.register");
    }


    public function smscheck(Request $request){

        $mobile = $request->mobile;

        if(empty($mobile)){
            return array(
                "error_code"    => "400001",
                "error_message" => "参数遗漏",
            );
        }

        //记得这里检查下redis

        $code = $this->createCode(6);

        //这里要进下redis

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

        //这里判断下code


        $oauth  = session('wechat.oauth_user.default');
        $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();

        $result = $wxuser->update(array(
            "truename" => $truename,
            "address"  => $address,
            "mobile"   => $mobile,
            "volunteer" => $volunteer,
            "partymember" => $partymember,
        ));

        if($result){

            return array(
                "error_code"    => "0",
                "error_message" => "success",
            );
            
        }else{
            return array(
                "error_code"    => "400002",
                "error_message" => "修改失败",
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
