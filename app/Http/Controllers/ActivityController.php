<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ActivityController extends Controller
{

    public function index(Request $request){

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
            $list["data"][$key]["participate"] = 0;
        }

        if($page === 1){
            return view("wechat.activitylist", compact("list", "type"));
        }else{
            return $list;
        }

    }

    public function detail(Request $request){
        $id   = $request->id;
        $mkey = "Wuliqiao-ActivityDetail-".$id;
        $json = @Redis::get($mkey);

        if(empty($json)){
            $json = Article::find($id)->toJson();
            @Redis::setex($mkey, 600, $json);
        }

        //这里要检查是否已经报名

        $info = json_decode($json, true);

        return view("wechat.activitydetail", compact("info"));

    }
}
