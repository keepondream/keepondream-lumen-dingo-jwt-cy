<?php
/**
 * Description: 服务层 业务服务抽象扩展类
 * Author: WangSx
 * DateTime: 2019-06-18 11:04
 */

namespace App\Services;


use Illuminate\Support\Facades\Redis;

abstract class Service
{
    protected $redis;

    public function __construct()
    {
        $this->redis = Redis::connection();
    }
}