<?php
/**
 * Description: 资源管理抽象类
 * Author: WangSx
 * DateTime: 2019-06-18 10:36
 */

namespace App\Common\BaseClasses;


use App\Common\Interfaces\ResourceManagerInterface;
use Illuminate\Support\Facades\Log;

abstract class ResourceManager implements ResourceManagerInterface
{
    protected $jwt;

    /**
     * 单例,存储ServiceManager
     * @var array
     */
    private static $_instanceArray = [];

    /**
     * Description: 返回ResourceManager的子类,这里为了智能提示正确,所以不条件 @param string $fullClassName
     * @return
     * Author: WangSx
     * DateTime: 2019-06-18 10:43
     * @throws \ReflectionException
     */
    protected static function _getInstance(string $fullClassName)
    {
        if (!array_key_exists($fullClassName, ResourceManager::$_instanceArray)) {
            $reflection_instance = new \ReflectionClass($fullClassName);
            ResourceManager::$_instanceArray[$fullClassName] = $reflection_instance->newInstance();
        }

        return ResourceManager::$_instanceArray[$fullClassName];
    }

    /**
     * 单例,存储各种服务层service
     * @var array
     */
    private $normalInstanceArray = [];

    public function __call($name, $arguments)
    {
        $className = ucfirst($name);
        try {
            if (!strstr($arguments[0], $className)) {
                Log::error('Wrong function name ' . compact('name', 'arguments'));
                throw new \Exception('Wrong function name');
            }

            if (!array_key_exists($className, $this->normalInstanceArray)) {
                $reflection_instance = new \ReflectionClass($arguments[0]);
                $this->normalInstanceArray[$className] = $reflection_instance->newInstance();
            }

            return $this->normalInstanceArray[$className];
        } catch (\Exception $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            //TODO: 异常类抛出
        }
    }
}