<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    public function index(Request $request){

        $page = intval($request->page) == 0? 1: intval($request->page);

        $list = Article::where("checked", 1)->where("deleted_at", null)
            ->orderBy("newstime", "desc")->paginate(1)->toJson();

        //首页用view 翻页用json
        if($page === 1){
            return view();
        }else{
            return $list;
        }

    }
}
