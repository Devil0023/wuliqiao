<?php

namespace App\Http\Controllers;

use App\Models\Article;
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
            echo 1;
        }

        echo 2;



        //首页用view 翻页用json
        if($page === 1){
            return view("wechat.news", compact("list"));
        }else{
            return $list;
        }

    }
}
