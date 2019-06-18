<?php
/**
 * Description: 资源管理接口
 * Author: WangSx
 * DateTime: 2019-06-18 10:29
 */

namespace App\Common\Interfaces;


interface ResourceManagerInterface
{
    /**
     * Description: 每个子类实现这个方法,用于返回具体Manager实例,
     * Author: WangSx
     * DateTime: 2019-06-18 10:31
     * @return mixed
     */
    public static function getInstance();
}