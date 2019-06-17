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
        $api->post('register', 'User\UserController@create');

        # 认证路由
        $api->group(['middleware' => 'api'], function ($api) {
            /**
             * 用户模块
             */
            $api->group(['namespace' => 'User'], function ($api) {
                $api->get('me', 'UserController@me');           # 用户详情
                $api->get('logout', 'UserController@logout');   # 退出
                $api->get('refresh', 'UserController@refresh'); # 刷新token
            });

        });
    });
});
