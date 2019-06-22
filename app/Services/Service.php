<?php
/**
 * Description: 服务层 业务服务抽象扩展类
 * Author: WangSx
 * DateTime: 2019-06-18 11:04
 */

namespace App\Services;


use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTGuard;

abstract class Service
{
    /**
     * @var \Illuminate\Redis\Connections\Connection
     */
    protected $redis = null;

    /**
     * @var JWTGuard|JWT
     */
    protected $api_jwt = null;

    /**
     * @var JWTGuard|JWT
     */
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

    /**
     * Author: WangSx
     * DateTime: 2019-06-22 10:26
     * @return \Illuminate\Redis\Connections\Connection
     */
    public function getRedis()
    {
        return $this->redis;
    }

    /**
     * Author: WangSx
     * DateTime: 2019-06-22 10:25
     * @return JWTGuard|JWT
     */
    public function getApiJwt()
    {
        return $this->api_jwt;
    }

    /**
     * Author: WangSx
     * DateTime: 2019-06-22 10:25
     * @return JWTGuard|JWT
     */
    public function getBackendJwt()
    {
        return $this->backend_jwt;
    }
}