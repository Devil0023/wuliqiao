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

    $router->resource("/activity/{type}/info", ActivityController::class);



    $router->resource("/pointsrule", PointsruleController::class);

    $router->resource("/points/{uid}/pointslog", PointslogController::class);
    $router->resource("/points/{uid}/vpointslog", PpointslogController::class);
    $router->resource("/points/{uid}/ppointslog", VpointslogController::class);
});
