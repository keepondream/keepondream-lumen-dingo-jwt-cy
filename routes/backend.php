<?php
/**
 * Description: 后台路由
 * Author: WangSx
 * DateTime: 2019-06-15 23:58
 */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {
    $api->group(['prefix' => 'b', 'namespace' => 'Backend'], function ($api) {
        $api->get('users', function () {
            return "this is b v1 ";
        });
    });
});
