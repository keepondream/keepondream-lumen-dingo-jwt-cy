<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-18 10:49
 */

namespace App\Services;


use App\Common\BaseClasses\ResourceManager;
use App\Services\AdminUser\AdminUserService;
use App\Services\User\UserService;

/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-18 10:52
 * Class ServiceManager
 * @package App\Services
 * 语法糖 方法申明 类名
 * @method UserService userService(string $fullClassName)
 * @method AdminUserService adminUserService(string $fullClassName)
 */
class ServiceManager extends ResourceManager
{

    /**
     * Description: 每个Manager类都只要实现以下这段即可,注意正确的返回值
     * Author: WangSx
     * DateTime: 2019-06-18 10:51
     * @return ServiceManager
     * @throws \ReflectionException
     */
    public static function getInstance(): ServiceManager
    {
        return parent::_getInstance(ServiceManager::class);
    }
}