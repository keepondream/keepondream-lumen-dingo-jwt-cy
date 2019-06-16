<?php
/**
 * Description: 应用1.0版本路由
 * Author: WangSx
 * DateTime: 2019-06-15 23:57
 */

$api = app('Dingo\Api\Routing\Router');

# 应用路由
$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {
    $api->group(['prefix' => 'f', 'namespace' => 'Frontend\V1'], function ($api) {
        #测试接口
        $api->get('version', function () {
            return response('this is app v1 code');
        });

        # 用户
        $api->post('login', 'UserController@login');
        $api->post('create', 'UserController@create');
        $api->get('getUser', 'UserController@getUser');
        $api->get('logout', 'UserController@logout');
    });
});
