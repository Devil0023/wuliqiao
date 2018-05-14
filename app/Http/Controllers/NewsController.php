<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Wxuser;
use App\Models\Pointsrule;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;


class NewsController extends Controller
{

    private $dailymission = 5;

    public function index(Request $request){

        $page = intval($request->page) == 0? 1: intval($request->page);
        $size = 5;

        $mkey = "Wuliqiao-Newslist-".$page."-".$size;
        $list = @Redis::get($mkey);

        if(empty($list)){
            $list = Article::where("checked", 1)->orderBy("newstime", "desc")->paginate($size)->toJson();
            @Redis::setex($mkey, 300, $list);
        }

        //获取任务信息
        $oauth        = session('wechat.oauth_user.default');
        $mission_key  = "Wuliqiao-Mission-".$oauth["id"];
        $mission_info = @Redis::get($mission_key);

        $mission["daily"]     = $this->dailymission;
        $mission["complete"] = count(array_filter(explode(",", $mission_info)));

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

        if(count($complete) >= $this->dailymission){
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

        if(Pointsrule::addPointsByRule(3, $wxuser->id)){

            $expire   = strtotime(date("Y-m-d")." 23:59:59") - time();

            $complete = array_filter(array_push($complete, $newsid));

            @Redis::setex($mission_key, $expire, implode(",", $complete));

            return array(
                "error_code"    => "0",
                "error_message" => "success",
            );

        }else{

            return array(
                "error_code"    => "400005",
                "error_message" => "每日任务保存失败",
            );

        }

    }

}
