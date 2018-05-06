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
    'middleware' => ['web', 'wuliqiao.cheatoauth', 'wechat.oauth', "wuliqiao.register"],

    ], function () {
        Route::get('/', function () {
            $user = session('wechat.oauth_user'); // 拿到授权用户资料
            dd($user);
        });
});