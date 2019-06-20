<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-20 23:54
 */

namespace App\Common\Constants;


class CONSTANT_ErrCodes
{

    /**
     * 错误码设置
     * @var array
     */
    public static $errCodes = [
        200 => '请求成功',
        202 => '请求成功但操作失败',
        400 => '未知错误',
        401 => '违背授权的操作',
        403 => '无权限访问',
        404 => '接口不存在',
        405 => '不允许的请求方式',
        500 => '服务器异常',
        # 以上为标准,以下为自定义

        # 用户相关 10000 ~ 19999
        10001 => '请求token无效',
        10002 => 'token失效,请重新登录',
        10003 => 'token不存在',
        10004 => 'token已入黑名单,请重新登录',


    ];

    /**
     * Description: 获取错误码详情
     * Author: WangSx
     * DateTime: 2019-06-21 00:17
     * @param int $code 错误码
     * @param bool $_   默认 false , true 则连带错误码返回
     * @return string
     */
    public static function getError(int $code = 400, bool $_ = false)
    {
        if (!isset(self::$errCodes[$code])) {
            $code = 400;
        }

        return ($_ ? "[{$code}] " : '') . self::$errCodes[$code];
    }

}