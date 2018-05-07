<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Wxuser;
use App\Models\Pointsrule;
//use App\Models\Pointslog;
//use App\Models\Ppointslog;
//use App\Models\Vpointslog;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;


class NewsController extends Controller
{

    public function index(Request $request){

        $page = intval($request->page) == 0? 1: intval($request->page);
        $size = 1;

        $mkey = "Wuliqiao-Newslist-".$page."-".$size;
        $list = @Redis::get($mkey);

        if(empty($list)){
            $list = Article::where("checked", 1)->where("deleted_at", null)
                ->orderBy("newstime", "desc")->paginate(1)->toJson();
            @Redis::setex($mkey, 300, $list);
        }

        //获取任务信息
        $oauth        = session('wechat.oauth_user.default');
        $mission_key  = "Wuliqiao-Mission-".$oauth["id"];
        $mission_info = @Redis::get($mission_key);

        $mission["daily"]     = 5;
        $mission["complete"] = count(explode(",", $mission_info));

        //首页用view 翻页用json
        if($page === 1){
            $list = json_decode($list, true);
            return view("wechat.news", compact("list", "mission"));
        }else{
            return $list;
        }

    }

    public function read(Request $request){

        $oauth   = session('wechat.oauth_user.default');
        $wxuser  = Wxuser::where("openid", "=", $oauth["id"])->first();

        $newsid  = intval($request->newsid);

        if($newsid === 0){
            return array(
                "error_code"    => "400001",
                "error_message" => "参数遗漏",
            );
        }

        $mission_key  = "Wuliqiao-Mission-".$oauth["id"];
        $mission_info = @Redis::get($mission_key);
        $complete     = explode(",", $mission_info);

        if(count($complete) >= 5){
            return array(
                "error_code"    => "400003",
                "error_message" => "今日阅读任务已完成",
            );
        }

        if(in_array($newsid, $complete)){
            return array(
                "error_code"    => "400004",
                "error_message" => "该文章已阅读",
            );
        }

        array_push($complete, $newsid);

        @Redis::setex($mission_key, 300, implode(",", array_filter($complete)));

        $points = Pointsrule::addPointsByRule(3, $wxuser->id, $oauth["id"]);

    }


    public function test(){

        $oauth   = session('wechat.oauth_user.default');
        $wxuser  = Wxuser::where("openid", "=", $oauth["id"])->first();


        $result  = Pointsrule::addPointsByRule(3, $wxuser->id);
        if($result){

            $wxuser  = Wxuser::where("openid", "=", $oauth["id"])->first()->toJson();
            echo $wxuser;

        }else{
            echo 2222;
        }


    }


}
