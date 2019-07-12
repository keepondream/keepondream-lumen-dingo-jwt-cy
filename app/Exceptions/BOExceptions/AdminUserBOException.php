<?php
/**
 * Description: 错误码区间类
 * Author: WangSx
 * DateTime: 2019-07-11 15:13
 */

namespace App\Exceptions\BOExceptions;


use App\Exceptions\Base\BaseSingleStatic;

/**
 * Author: WangSx
 * DateTime: 2019-07-11 17:08
 * Class AdminUserBOException
 * @package App\Exceptions\BOExceptions
 */
class AdminUserBOException extends BaseSingleStatic
{
    /**
     * 当前异常类 code 最小取值范围
     */
    const CODE_MIN = 11001;

    /**
     * 当前异常类 code 最大取值范围
     */
    const CODE_MAX = 11999;

    /**
     * Description: 获取当前类最小错误码
     * Author: WangSx
     * DateTime: 2019-07-11 17:06
     * @return int
     */
    protected function getCodeMin(): int
    {
        return self::CODE_MIN;
    }

    /**
     * Description: 获取当前类最大错误码
     * Author: WangSx
     * DateTime: 2019-07-11 17:07
     * @return int
     */
    protected function getCodeMax(): int
    {
        return self::CODE_MAX;
    }

}

