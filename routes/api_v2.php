<?php
/**
 * Description: 应用接口V2版本迭代
 * Author: WangSx
 * DateTime: 2019-06-16 00:57
 * v2 路由切换增加 headers ("Accept":"application/[env:API_STANDARDS_TREE].[env:API_SUBTYPE].[接口版本]+json")
 */
$api = app('Dingo\Api\Routing\Router');

$api->version('v2', ['namespace' => 'App\Http\Controllers\Frontend\V2'], function ($api) {
    $api->group(['prefix' => 'f', 'namespace' => ''], function ($api) {
        $api->get('version', function () {
            return response('this is app v2 code');
        });
        $api->get('admin', function () {
            return "this is vi app";
        });
    });
});