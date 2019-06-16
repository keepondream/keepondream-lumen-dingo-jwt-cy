<?php
/**
 * Description: 后台路由
 * Author: WangSx
 * DateTime: 2019-06-15 23:58
 */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {
    $api->group(['prefix' => 'b', 'namespace' => 'Backend\V1'], function ($api) {

        # 管理员
        $api->post('login', 'AdminUserController@login');
        $api->post('create', 'AdminUserController@create');
    });
});
