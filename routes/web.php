<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return "";
});

Route::group([
    "prefix" => "wechat",
    'middleware' => ['web', 'wuliqiao.cheatoauth', 'wechat.oauth', 'wuliqiao.register'],

    ], function () {
        //主页用户中心
        Route::get('/', 'WuliqiaoController@index');
        Route::get('/index', 'WuliqiaoController@index');
        Route::get('/usercenter', 'WuliqiaoController@index');
        Route::get('/usercenter/index', 'WuliqiaoController@index');

        //修改信息&注册
        Route::get('/usercenter/setting', 'WuliqiaoController@setting');
        Route::get('/usercenter/register', 'WuliqiaoController@register');
        Route::post('/usercenter/smscheck', 'WuliqiaoController@smscheck');
        Route::post('/usercenter/updateinfo', 'WuliqiaoController@updateinfo');

        Route::get("/usercenter/pointslog",  "WuliqiaoController@pointslog");
        Route::get("/usercenter/ppointslog", "WuliqiaoController@ppointslog");
        Route::get("/usercenter/vpointslog", "WuliqiaoController@vpointslog");

        //新闻
        Route::get('/news', "NewsController@index");
        Route::get('/news/index', "NewsController@index");
        Route::post('/news/read', "NewsController@read");


        //活动
        Route::get('/activity', "ActivityController@index");
        Route::get('/activity/index', "ActivityController@index");
    }
);