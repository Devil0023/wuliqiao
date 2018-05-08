<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ActivityController extends Controller
{
    public $type  = 1;

    public function __construct(Request $request){
        switch($request->type){
            case "community": $this->type = 1; break;
            case "publicservice": $this->type = 2; break;
            default: $this->type = 1;
        }


    }

    public function index(Request $request){

        $type = $this->type;

        $page = intval($request->page) === 0? 1: intval($request->page);
        $mkey = "Wuliqiao-Activity-".$this->type."-".$page;

        $json = @Redis::get($mkey);

        if(empty($json)){
            $json = Activity::where("type", $this->type)->where("checked", 1)->orderBy("id", "desc")->paginate(3)->toJson();;
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
}
