<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//$router->get('/', function () use ($router) {
//    return $router->app->version();
//});

# 应用接口所有异常统一处理,可扩展hook
app('api.exception')->register(function (Exception $exception) {
//    $request = \Illuminate\Http\Request::capture();
    $request = \Dingo\Api\Http\Request::capture();
    return app('App\Exceptions\Handler')->render($request, $exception);
});

require __DIR__ . DIRECTORY_SEPARATOR . 'api.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'api_v2.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'backend.php';
