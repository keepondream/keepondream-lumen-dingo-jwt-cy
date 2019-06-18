<?php
/**
 * Description: 控制器扩展接口,增加公有静态获取服务接口.
 * Author: WangSx
 * DateTime: 2019-06-18 11:40
 */

namespace App\Common\Interfaces;


interface ControllerInterface
{
    /**
     * Description: 子类控制器必须实现获取自己的服务,为了智能提示,不@return
     * Author: WangSx
     * DateTime: 2019-06-18 11:43
     */
    public static function getService();
}