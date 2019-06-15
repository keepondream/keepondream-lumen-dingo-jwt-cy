<?php
/**
 * Description: 应用1.0版本路由
 * Author: WangSx
 * DateTime: 2019-06-15 23:57
 */

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {
    #测试接口
    $api->get('version', function () {
        return response('this is app v1 code');
    });

    #应用接口
    $api->group(['prefix' => 'api', 'namespace' => 'Frontend'], function ($api) {
        $api->get('user', function () {
            return "this is vi app";
        });
    });
});
