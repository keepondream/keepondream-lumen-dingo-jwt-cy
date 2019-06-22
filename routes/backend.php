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
                $api->post('create', 'AdminUserController@create');     # 创建管理员
                $api->get('me', 'AdminUserController@me');              # 用户详情
                $api->get('logout', 'AdminUserController@logout');      # 退出
                $api->get('refresh', 'AdminUserController@refresh');    # 刷新token
                $api->get('update', 'AdminUserController@update');      # 更新

            });

        });
    });
});
