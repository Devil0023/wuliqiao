<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    //$router->get('/', 'HomeController@index');

    $router->get('/', PrizeController::class);
    $router->resource('/prize', PrizeController::class);
    $router->resource('/wxuser', WxuserController::class);
    $router->resource('/article', ArticleController::class);

    $router->get('/qrcode/activity/{aid}', 'ActivityController@qrcode');

    $router->resource("/activity/{type}/info", ActivityController::class);
    $router->resource("/activity/{aid}/participate", ParticipateController::class);


    $router->resource("/pointsrule", PointsruleController::class);

    $router->resource("/points/{uid}/pointslog", PointslogController::class);
    $router->resource("/points/{uid}/vpointslog", VpointslogController::class);
    $router->resource("/points/{uid}/ppointslog", PpointslogController::class);

    $router->resource("/prize/{pid}/exchange", ExchangeController::class);

});
