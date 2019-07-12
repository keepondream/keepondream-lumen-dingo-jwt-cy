<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-07-12 15:14
 */

namespace App\Exceptions\Mapping;


use App\Exceptions\Base\ErrorMapBase;

class SysBOErrCodeMsgMapping extends ErrorMapBase
{
    const DEFAULT_ERROR = 1000;
    const TOKEN_TIMEOUT = 1001;
    const TOKEN_IS_BLACK_LIST = 1002;

    protected function getErrMsgMapping()
    {
        return [
            self::TOKEN_TIMEOUT => 'token失效,请重新登录',
            self::TOKEN_IS_BLACK_LIST => 'token已是黑名单'
        ];
    }
}