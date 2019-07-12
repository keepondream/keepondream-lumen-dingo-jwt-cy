<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-07-11 16:54
 */

namespace App\Exceptions\Base;


class ExceptionContext
{
    protected  $_className;
    protected  $_message;
    protected  $_stack;

    public function __construct(string $className,string $message,string $stack)
    {
        $this->_className = $className;
        $this->_message = $message;
        $this->_stack = $stack;
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->_className;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @return mixed
     */
    public function getStack()
    {
        return $this->_stack;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        // NOTICE:  处只支持类属性为基础类型或对象，支持一层对象数组
        $vars = get_object_vars($this);
        foreach ($vars as $key=>$var) {
            if (is_array($var)) {
                foreach ($var as $c_key=>$c_var) {
                    if (is_object($c_var)) {
                        $vars[$key][$c_key] = $c_var->toArray();
                    }
                }
            }
            elseif (is_object($var)) {
                $vars[$key] = $var->toArray();
            }
        }
        return $vars;
    }

    public static function build($className, $message, $stack)
    {
        return new self($className,$message,$stack);
    }

}