<?php
/**
 * Description: 公共函数文件
 * Author: WangSx
 * DateTime: 2019-06-19 13:44
 */

use App\Common\Helper;

if (!function_exists('success')) {
    function success($data = [], $msg = '请求成功', $code = 200)
    {
        return Helper::success($data, $msg, $code);
    }
}

if (!function_exists('failed')) {
    function failed($msg = '请求失败!~', $code = 400, $data = [])
    {
        return Helper::failed($msg, $code, $data);
    }
}