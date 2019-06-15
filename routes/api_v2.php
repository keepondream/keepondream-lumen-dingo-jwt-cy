<?php
/**
 * Description: 应用接口V2版本迭代
 * Author: WangSx
 * DateTime: 2019-06-16 00:57
 */
$api = app('Dingo\Api\Routing\Router');

$api->version('v2', ['namespace' => 'App\Http\Controllers'], function ($api) {
    $api->get('version', function () {
        return response('this is app v2 code');
    });

    $api->group(['prefix' => 'api', 'namespace' => 'Frontend'], function ($api) {
        $api->get('admin', function () {
            return "this is vi app";
        });
    });
});