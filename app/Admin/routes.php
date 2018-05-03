<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resource('/prize', PrizeController::class);
    $router->resource('/wxuser', WxuserController::class);
    $router->resource('/article', ArticleController::class);

    $router->resource("/article/{type}", ActivityController::class);
});
