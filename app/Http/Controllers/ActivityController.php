<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Participate;
use App\Models\Wxuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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
        $info["participate"] = Participate::chkInfo($id, $wxuser->id, "participate");
        $info["timeinfo"]    = $this->getTimestring(strtotime($info["created_at"]));

        return view("wechat.activitydetail", compact("info"));

    }

    public function participate(Request $request){

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


        //这里要检查是否已经报名
        if(Participate::chkInfo($id, $wxuser->id, "participate") === 1){
            return array(
                "error_code"    => "400010",
                "error_message" => "已报名",
            );
        }

        $result = Participate::create(array(
            "uid"              => $wxuser->id,
            "openid"           => $wxuser->openid,
            "aid"               => $id,
            "participate"      => 1,
            "sign"              => 0,
            "participatetime" => time(),
            "signtime"         => 1,
        ));

        if($result){
            return array(
                "error_code"    => "0",
                "error_message" => "success",
            );
        }else{
            return array(
                "error_code"    => "400011",
                "error_message" => "报名失败",
            );
        }

    }

    private function getTimestring($time){
        $now   = time();
        $delta = $time - $now;

        if($delta <= 3600){
            return intval( $delta / 60)."分钟前";
        }elseif($delta <= 7200 && $delta > 3600){
            return "1小时前";
        }else{
            return date("Y-m-d H:i");
        }
    }
}
