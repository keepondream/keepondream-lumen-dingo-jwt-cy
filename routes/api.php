<?php
/**
 * Description: 应用1.0版本路由
 * Author: WangSx
 * DateTime: 2019-06-15 23:57
 */

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {
    $api->group(['prefix' => 'f', 'namespace' => 'Frontend\V1'], function ($api) {

        # 用户登录
        $api->post('login', 'User\UserController@login');
        # 用户注册

        # 认证路由
        $api->group(['middleware' => 'auth:api'], function ($api) {

            /**
             * 用户模块
             */
            $api->group(['namespace' => 'User'], function ($api) {
                $api->post('create', 'UserController@create');
                $api->get('getUser', 'UserController@getUser');
                $api->get('logout', 'UserController@logout');
            });

        });
    });
});
