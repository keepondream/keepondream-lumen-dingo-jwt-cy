<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-07-11 15:19
 */

namespace App\Exceptions\Base;

use App\Exceptions\Mapping\BOErrCodeMsgMapping;
use Exception;

/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-07-11 16:00
 * Class BaseSingleStatic
 * @package App\Exceptions\Base
 * @method static Exception errCode($code) 错误码
 */
abstract class BaseSingleStatic extends BaseException
{
    /**
     * 所有错误类单例集合
     * @var array
     */
    private static $single_list = [];

    /**
     * Description: 静态初始化+异常初始化
     * Author: WangSx
     * DateTime: 2019-07-11 16:27
     * @param $className
     * @param $code
     * @return mixed
     * @throws Exception
     */
    public static function init($className, $code)
    {
        if (!array_key_exists($className, self::$single_list)) {
            self::$single_list[$className] = new static('', $code);
        }

        return self::$single_list[$className];
    }

    /**
     * Description: 定义静态调用方法
     * Author: WangSx
     * DateTime: 2019-07-11 16:28
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        $className = get_called_class();
        if ($name == 'errCode') {
            reset($arguments);
            $code = current($arguments);
            throw static::init($className, $code);
        } else {
            throw new \Exception('undefined method : ' . $name);
        }
    }

    protected function setErrCodeMsgMapping()
    {
        return new BOErrCodeMsgMapping();
    }

}

