<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    public function index(Request $request){

        $page = intval($request->page) == 0? 1: intval($request->page);

        //首页用view 翻页用json
        if($page === 1){

        }

        //要加缓存
        $list = Article::where("checked", 1)->where("deleted_at", null)
            ->orderBy("newstime", "desc")->paginate(1)->toJson();

        echo $list; die;
    }
}
