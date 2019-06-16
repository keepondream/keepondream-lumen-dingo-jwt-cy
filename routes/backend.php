<?php
/**
 * Description: 后台路由
 * Author: WangSx
 * DateTime: 2019-06-15 23:58
 */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {
    $api->group(['prefix' => 'b', 'namespace' => 'Backend\V1'], function ($api) {

        # 管理员登录
        $api->post('login', 'AdminUser\AdminUserController@login');

        # 认证路由
        $api->group(['middleware' => 'auth:backend'], function ($api) {

            /**
             * 管理员模块
             */
            $api->group(['namespace' => 'AdminUser'], function ($api) {
                $api->post('create', 'AdminUserController@create');
                $api->get('getUser', 'AdminUserController@getUser');
                $api->get('logout', 'AdminUserController@logout');
            });

        });
    });
});
