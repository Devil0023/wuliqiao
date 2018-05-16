<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Participate;
use App\Models\Wxuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use DB;

class ActivityController extends Controller
{

    public function index(Request $request){

        $oauth  = session('wechat.oauth_user.default');
        $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();

        switch($request->type){
            case "community": $type = 1; break;
            case "publicservice": $type = 2; break;
            default: $type = 1;
        }


        $page = intval($request->page) === 0? 1: intval($request->page);
        $mkey = "Wuliqiao-Activity-".$type."-".$page;

        $json = @Redis::get($mkey);

        if(empty($json)){
            $json = Activity::where("type", $type)->where("checked", 1)->orderBy("id", "desc")->paginate(3)->toJson();;
            @Redis::setex($mkey, 600, $json);
        }

        $list  = json_decode($json, true);

        //这里用来检查是否已经参加
        foreach($list["data"] as $key => $val){
            $list["data"][$key]["participate"] = Participate::chkInfo($val["id"], $wxuser->id, "participate");
        }

        if($page === 1){
            return view("wechat.activitylist", compact("list", "type"));
        }else{
            return $list;
        }

    }

    public function detail(Request $request){

        $oauth  = session('wechat.oauth_user.default');
        $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();

        $id   = $request->id;
        $mkey = "Wuliqiao-ActivityDetail-".$id;
        $json = @Redis::get($mkey);

        if(empty($json)){
            $activity = Activity::find($id);

            if(is_null($activity)){
                return array(
                    "error_code"    => "400012",
                    "error_message" => "活动不存在",
                );
            }

            $json = $activity->toJson();

            @Redis::setex($mkey, 600, $json);
        }

        //这里要检查是否已经报名
        $info = json_decode($json, true);
        $info["participate"]  = Participate::chkInfo($id, $wxuser->id, "participate");
        $info["timeinfo"]     = $this->getTimestring(strtotime($info["created_at"]));
        $info["activitytime"] = date("Y-m-d H:i", strtotime($info["activitytime"]));

        return view("wechat.activitydetail", compact("info"));

    }

    public function participate(Request $request){

        $now    = time();

        $id     = $request->id;
        $mkey   = "Wuliqiao-ActivityDetail-".$id;
        $json   = @Redis::get($mkey);

        if(empty($json)){
            $activity = Activity::find($id);

            if(is_null($activity)){
                return array(
                    "error_code"    => "400012",
                    "error_message" => "活动不存在",
                );
            }

            $json = $activity->toJson();

            @Redis::setex($mkey, 600, $json);
        }

        $ainfo  = json_decode($json, true);

        if(strtotime($ainfo["stime"]) > $now){
            return array(
                "error_code"    => "400017",
                "error_message" => "活动报名未开始",
            );
        }

        if(strtotime($ainfo["etime"]) <= $now){
            return array(
                "error_code"    => "400018",
                "error_message" => "活动报名已结束",
            );
        }

        $oauth  = session('wechat.oauth_user.default');
        $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();


        //这里要检查是否已经报名
        if(Participate::chkInfo($id, $wxuser->id, "participate") === 1){
            return array(
                "error_code"    => "400010",
                "error_message" => "已报名",
            );
        }

        DB::beginTransaction();
        try{

            if($ainfo["limitation"] > 0){

                $result = Activity::find($id)->where("limitation_left", ">", 0)->decrement("limitation_left", 1);

                if($result){
                    Participate::create(array(
                        "uid"              => $wxuser->id,
                        "openid"           => $wxuser->openid,
                        "aid"               => $id,
                        "participate"      => 1,
                        "sign"              => 0,
                        "participatetime" => date("Y-m-d H:i:s"),
                    ));

                    DB::commit();

                    return array(
                        "error_code"    => "0",
                        "error_message" => "success",
                    );
                }else{

                    DB::rollBack();

                    return array(
                        "error_code"    => "400019",
                        "error_message" => "活动报名人数已满",
                    );

                }

            }else{


                Participate::create(array(
                    "uid"              => $wxuser->id,
                    "openid"           => $wxuser->openid,
                    "aid"               => $id,
                    "participate"      => 1,
                    "sign"              => 0,
                    "participatetime" => date("Y-m-d H:i:s"),
                ));

                DB::commit();

                return array(
                    "error_code"    => "0",
                    "error_message" => "success",
                );
            }

        }catch(Exception $e){


            DB::rollBack();

            return array(
                "error_code"    => "400011",
                "error_message" => "报名失败",
            );
        }

    }

    public function sign(Request $request){
        $id     = $request->id;
        $mkey   = "Wuliqiao-ActivityDetail-".$id;
        $json   = @Redis::get($mkey);

        if(empty($json)){
            $activity = Activity::find($id);

            if(is_null($activity)){
                return array(
                    "error_code"    => "400012",
                    "error_message" => "活动不存在",
                );
            }

            $json = $activity->toJson();

            @Redis::setex($mkey, 600, $json);
        }

        $oauth  = session('wechat.oauth_user.default');
        $wxuser = Wxuser::where("openid", "=", $oauth["id"])->first();

        if(Participate::chkInfo($id, $wxuser->id, "participate") === 0){
            return array(
                "error_code"    => "400020",
                "error_message" => "该活动未报名",
            );
        }

        $result = Participate::where(array(
            "uid"              => $wxuser->id,
            "openid"           => $wxuser->openid,
            "aid"               => $id,
            "participate"      => 1,
            "sign"             => 0,
        ))->update(array(
            "sign"             => 1,
            "signtime"        => date("Y-m-d H:i:s"),
        ));

        if($result){
            redirect("/wechat/activity/detail/".$id);
        }else{
            return array(
                "error_code"    => "400021",
                "error_message" => "签到失败",
            );
        }

    }

    private function getTimestring($time){
        $now   = time();
        $delta = $now - $time;

        if($delta <= 3600){
            return intval( $delta / 60)."分钟前";
        }elseif($delta <= 7200 && $delta > 3600){
            return "1小时前";
        }else{
            return date("Y-m-d H:i");
        }
    }
}
