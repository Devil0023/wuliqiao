<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PrizeController extends Controller
{

    public function index(){

        $mkey = "Wuliqiao-Prizelist";
        $json = @Redis::get($mkey);

        if(empty($json)){
            $list = Prize::where("checked", "=", 1)->where("num", ">", 0)->orderBy("newstime", "desc")->get();

            echo $list->toJson();
        }


    }
}
