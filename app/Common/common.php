<?php
/**
 * Description: 公共函数文件
 * Author: WangSx
 * DateTime: 2019-06-19 13:44
 */

use App\Common\Helper;

if (!function_exists('success')) {
    function success($data = [], $message = '请求成功', $status_code = 200)
    {
        return Helper::success($data, $message, $status_code);
    }
}

if (!function_exists('failed')) {
    function failed($message = '请求失败!~', $status_code = 400, $data = [])
    {
        return Helper::failed($message, $status_code, $data);
    }
}