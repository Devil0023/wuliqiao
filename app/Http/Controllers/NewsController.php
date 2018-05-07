<?php

namespace App\Http\Controllers;

use App\Models\Article;

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
        $mission["complete"] = count(array_filter(explode(",", $mission_info)));

        //首页用view 翻页用json
        if($page === 1){
            $list = json_decode($list, true);
            return view("wechat.news", compact("list", "mission"));
        }else{
            return $list;
        }

    }
}
