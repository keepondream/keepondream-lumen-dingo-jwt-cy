<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-07-10 15:36
 */

namespace App\Exceptions\Base;

use Illuminate\Support\Facades\Log;
use RuntimeException;
use Exception;

abstract class BaseException extends RuntimeException
{
    abstract protected function getCodeMin();

    abstract protected function getCodeMax();

    abstract protected function setErrCodeMsgMapping();

    protected static $err_map_instance = null;

    protected function getErrMapInstance()
    {
//        if (is_null(self::$err_map_instance)) {
            self::$err_map_instance = $this->setErrCodeMsgMapping();
//        }

        return self::$err_map_instance;
    }

    /**
     * BaseException constructor.
     * @param string $message
     * @param int $code
     * @param Exception $previous
     * @throws Exception
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {

        if ($this->validateErrorCode($code)) {
            $format_message = $this->getErrorMsg($code);
            if (empty($format_message)) {
                $format_message = $message;
            }
            Log::error($format_message);
            parent::__construct($format_message, $code, $previous);
        } else {
            throw new \Exception('构造Excepion失败，因为传入的Code不在指定范围内 , code : ' . $code . ' , name: ' . get_called_class(), 0, $this->getPrevious());
        }
    }

    protected function getErrorMsg(int $code, $context = null)
    {
        return $this->getErrMapInstance()->getDisplayErrMsg($code, $context);
    }

    protected function validateErrorCode(int $code): bool
    {
        return $this->between($code, $this->getCodeMin(), $this->getCodeMax());
    }

    protected function between(int $code, int $min, int $max)
    {
        return $code <= $max && $code >= $min;
    }
}