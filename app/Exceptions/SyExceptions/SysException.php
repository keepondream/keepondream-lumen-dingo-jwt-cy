<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-07-12 15:21
 */

namespace App\Exceptions\SyExceptions;


use App\Exceptions\Base\BaseSingleStatic;
use App\Exceptions\Mapping\SysBOErrCodeMsgMapping;

class SysException extends BaseSingleStatic
{
    /**
     * 当前异常类 code 最小取值范围
     */
    const CODE_MIN = 1000;

    /**
     * 当前异常类 code 最大取值范围
     */
    const CODE_MAX = 9999;

    /**
     * Description: 获取最小范围错误码
     * Author: WangSx
     * DateTime: 2019-07-12 15:23
     * @return int
     */
    protected function getCodeMin()
    {
        return self::CODE_MIN;
    }

    /**
     * Description: 获取最大范围错误码
     * Author: WangSx
     * DateTime: 2019-07-12 15:23
     * @return int
     */
    protected function getCodeMax()
    {
        return self::CODE_MAX;
    }

    protected function setErrCodeMsgMapping()
    {
        return new SysBOErrCodeMsgMapping();
    }
}