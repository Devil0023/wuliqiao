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
    return view('welcome');
});

Route::group([
    "prefix" => "wechat",
    'middleware' => ['web', 'wuliqiao.cheatoauth', 'wechat.oauth', 'wuliqiao.register'],

    ], function () {
        //主页用户中心
        Route::get('/', 'WuliqiaoController@index');
        Route::get('/index', 'WuliqiaoController@index');

        
    }
);