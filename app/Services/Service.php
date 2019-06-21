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
    protected $redis = null;

    protected $api_jwt = null;

    protected $backend_jwt = null;

    public function __construct()
    {
        # 实例化Redis
        if (is_null($this->redis)) {
            $this->redis = Redis::connection();
        }

        # 实例应用api jwt
        if (is_null($this->api_jwt)) {
            $this->api_jwt = app('auth')->guard('api');
        }

        # 实例后台backend jwt
        if (is_null($this->backend_jwt)) {
            $this->backend_jwt = app('auth')->guard('backend');
        }

    }

    public function getRedis()
    {
        return $this->redis;
    }

    public function getApiJwt()
    {
        return $this->api_jwt;
    }

    public function getBackendJwt()
    {
        return $this->backend_jwt;
    }
}