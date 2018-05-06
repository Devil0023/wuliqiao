<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    public function index(){

        //要加缓存
        $list = Article::where("checked", 1)->where("deleted_at", null)
            ->orderBy("newstime", "desc")->paginate(1)->toJson(); //

        echo $list; die;
    }
}
