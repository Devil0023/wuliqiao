<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Prize;
use App\Models\Wxuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PrizeController extends Controller
{

    public function index(){

        $mkey = "Wuliqiao-Prizelist";
        $json = @Redis::get($mkey);
        $list = array();


        if(empty($json)){
            $prize = Prize::where("checked", "=", 1)->where("num", ">", 0)->orderBy("etime", "asc")->get();
            if(!is_null($prize)){
                $list = $prize->toArray();
            }

            @Redis::setex($mkey, 600, json_encode($list));
        }else{
            $list = json_decode($json, true);
        }


        return view("wechat.prize", compact("list"));
    }

    public function exchange(Request $request){

        $oauth  = session('wechat.oauth_user.default');
        $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();




    }

}
