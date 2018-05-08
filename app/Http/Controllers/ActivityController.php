<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function index(){
        echo $this->type;

    }
}
