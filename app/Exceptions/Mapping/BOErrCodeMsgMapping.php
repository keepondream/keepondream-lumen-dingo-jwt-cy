<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-07-11 15:00
 */

namespace App\Exceptions\Mapping;


use App\Exceptions\Base\ErrorMapBase;

class BOErrCodeMsgMapping extends ErrorMapBase
{
    # region 默认系统异常
    const DEFAULT_ERROR = 10000;
    # endregion

    # region 后台用户 11001 - 11999
    const ADMIN_USER_LOGIN_NOT_FOUND = 11001;
    const ADMIN_USER_LOGIN_FAILED = 11002;
    const ADMIN_USER_REFRESH_FAILED = 11003;

    # endregion

    protected function getErrMsgMapping()
    {
        return [
            # region 系统异常
            self::DEFAULT_ERROR => '系统异常',
            # endregion

            # region 后台用户
            self::ADMIN_USER_LOGIN_NOT_FOUND => '无此用户',
            self::ADMIN_USER_LOGIN_FAILED => '账号或密码错误',
            self::ADMIN_USER_REFRESH_FAILED => 'token刷新失败'
            # endregion
        ];
    }
}