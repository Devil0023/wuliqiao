<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Pointslog;
use App\Models\Prize;
use App\Models\Wxuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

use DB;

class PrizeController extends Controller
{

    public $exchange_limit = 2;

    public function index(){

        $now  = time();

        $mkey = "Wuliqiao-Prizelist";
        $json = @Redis::get($mkey);
        $list = array();


        if(empty($json)){

            $prize = Prize::where("checked", "=", 1)->where("num", ">", 0)
                ->where("stime" , "<", date("Y-m-d H:i:s", $now))->where("etime" , ">", date("Y-m-d H:i:s", $now))
                ->orderBy("etime", "asc")->get();

            if(!is_null($prize)){
                $list = $prize->toArray();
            }

            @Redis::setex($mkey, 600, json_encode($list));
        }else{
            $list = json_decode($json, true);
        }

        $oauth  = session('wechat.oauth_user.default');
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

        return view("wechat.prize", compact("list", "now", "uinfo"));
    }

    public function exchange(Request $request){
        $now    = time();
        $id     = $request->id;
        $prize  = Prize::find($id);

        if(is_null($prize)){
            return array(
                "error_code"    => "400013",
                "error_message" => "奖品不存在或已结束兑换",
            );
        }

        if($prize->num <= 0 || strtotime($prize->stime) > $now || strtotime($prize->etime) < $now ){
            return array(
                "error_code"    => "400013",
                "error_message" => "奖品不存在或已结束兑换",
            );
        }


        $oauth  = session('wechat.oauth_user.default');
        $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();

        if($wxuser->points < $prize->cost){
            return array(
                "error_code"    => "400022",
                "error_message" => "积分不够兑换",
            );
        }

        $mkey   = "Wuliqiao-PrizeExchange-".$wxuser->id."-".$id;

        if(@Redis::get($mkey)){
            return array(
                "error_code"    => "400014",
                "error_message" => "同一奖品一周内不得反复兑换",
            );
        }

        $count = Exchange::where(array(
            "pid" => $id,
            "uid" => $wxuser->id,
        ))->count();

        if($count >= $this->exchange_limit){
            return array(
                "error_code"    => "400015",
                "error_message" => "同一奖品不得兑换超过".$this->exchange_limit."次",
            );
        }


        @Redis::setex($mkey, 30, 1);

        DB::beginTransaction();
        try{

            $result1 = Prize::where("id", "=", $id)->where("num", ">", 0)->decrement("num", 1);

            if($result1){

                Exchange::create(array(
                    "uid" => $wxuser->id,
                    "pid" => $id,
                    "openid" => $wxuser->openid,
                ));

                Wxuser::find($wxuser->id)->update(array(
                    "points" => $wxuser->points - $prize->cost
                ));

                Pointslog::create(array(
                    "uid"     => $wxuser->id,
                    "openid" => $wxuser->openid,
                    "delta"  => 0 - $prize->cost,
                    "desc"   => "兑换奖品：".$prize->prize,
                ));

                DB::commit();

                return array(
                    "error_code"    => "0",
                    "error_message" => "success",
                );

            }else{


                DB::rollBack();
                @Redis::del($mkey);

                return array(
                    "error_code"    => "400016",
                    "error_message" => "奖品已被兑换完",
                );
            }

        }catch(Exception $e){

            DB::rollBack();
            @Redis::del($mkey);

            return array(
                "error_code"    => "400016",
                "error_message" => "奖品兑换失败",
            );

        }
    }

}
