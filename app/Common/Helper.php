<?php
/**
 * Description: 全局通用辅助函数
 * Author: WangSx
 * DateTime: 2019-06-18 15:05
 */

namespace App\Common;


use App\Common\BaseClasses\ResourceManager;
use App\Common\Constants\CONSTANT_RedisKey;
use App\Models\AdminUser;
use App\Models\User;
use App\Services\Service;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Log;

/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-19 15:17
 * Class Helper
 * @package App\Common
 *
 */
class Helper extends ResourceManager
{
    use Helpers;

    /**
     * Description: 单例自己
     * Author: WangSx
     * DateTime: 2019-06-19 15:02
     * @return Helper
     * @throws \ReflectionException
     */
    public static function getInstance(): Helper
    {
        return parent::_getInstance(Helper::class);
    }

    /**
     * Description: 生成前台api用户token并单点登录存储,
     * Author: WangSx
     * DateTime: 2019-06-21 17:27
     * @param User $user
     * @param Service $service
     * @return mixed
     */
    public static function fromUser(User $user, Service $service)
    {
        $arr = [];
        $access_token = $service->getApiJwt()->fromUser($user);
        $token_type = 'Bearer';
        $expire_time = time() + ($service->getApiJwt()->factory()->getTTL() * 60);

        if (!empty($access_token)) {
            Helper::updateUserRedisToken($user->mobile, $access_token, $service);
            $arr = compact('access_token', 'token_type', 'expire_time');
        }

        return $arr;
    }

    /**
     * Description: 生成后台backend用户token并单点登录存储,
     * Author: WangSx
     * DateTime: 2019-06-21 17:29
     * @param AdminUser $user
     * @param Service $service
     * @return mixed
     */
    public static function fromAdminUser(AdminUser $user, Service $service)
    {
        $arr = [];
        $access_token = $service->getBackendJwt()->fromUser($user);
        $token_type = 'Bearer';
        $expire_time = time() + ($service->getApiJwt()->factory()->getTTL() * 60);

        if (!empty($access_token)) {
            Helper::updateAdminUserRedisToken($user->mobile, $access_token, $service);
            $arr = compact('access_token', 'token_type', 'expire_time');
        }

        return $arr;
    }

    /**
     * Description: 单点登录,用于记录api用户token,当token变更后使旧token加入黑名单
     * Author: WangSx
     * DateTime: 2019-06-21 15:11
     * @param int $mobile
     * @param string $token
     * @param Service $service 任意service的子类 即所有service都可以
     */
    public static function updateUserRedisToken(int $mobile, string $token, Service $service)
    {
        $redis = $service->getRedis();
        # 将旧token 加入黑名单,使其登录失效
        if ($oldToken = $redis->hget(CONSTANT_RedisKey::AUTH_USER_TOKEN, $mobile)) {
            # 捕获过期令牌,防止程序中断
            try {
                $service->getApiJwt()->setToken($oldToken)->invalidate();
            } catch (\Exception $e) {
                Log::debug('api单点登录 ,token过期 ' . $e->getMessage());
            }
        }
        # 设置新token
        $redis->hset(CONSTANT_RedisKey::AUTH_USER_TOKEN, $mobile, $token);
    }

    /**
     * Description: 单点登录,用于记录backend用户token,当token变更后使旧token加入黑名单
     * Author: WangSx
     * DateTime: 2019-06-21 15:17
     * @param int $mobile
     * @param string $token
     * @param Service $service 任意service的子类 即所有service都可以
     */
    public static function updateAdminUserRedisToken(int $mobile, string $token, Service $service)
    {
        $redis = $service->getRedis();
        # 旧token 入黑名单
        if ($oldToken = $redis->hget(CONSTANT_RedisKey::AUTH_ADMIN_USER_TOKEN, $mobile)) {
            # 捕获过期令牌
            try {
                $service->getBackendJwt()->setToken($oldToken)->invalidate();
            } catch (\Exception $e) {
                Log::debug('单点登录 backend ,token过期 ' . $e->getMessage());
            }
        }
        # 设置新token
        $redis->hset(CONSTANT_RedisKey::AUTH_ADMIN_USER_TOKEN, $mobile, $token);
    }

    /**
     * Description: 200 正确响应 成功 公共方法
     * 与common.php 中的success函数一致
     * Author: WangSx
     * DateTime: 2019-06-19 15:06
     * @param array $data 响应数据数组
     * @param string $message 响应消息
     * @param int $status_code 响应编码
     * @return mixed
     * @throws \ReflectionException
     */
    public static function success($data = [], $message = '请求成功!', $status_code = Response::HTTP_OK)
    {
        $time = time();
        empty($data) && $data = [];
        return static::getInstance()->response->array(compact('status_code', 'message', 'time', 'data'));
    }

    /**
     * Description: 200 正确响应 失败 公共方法
     * 与common.php 中的 failed 函数一致
     * Author: WangSx
     * DateTime: 2019-06-19 15:12
     * @param string $message 响应消息
     * @param int $status_code 响应编码
     * @param array $data 响应数据数组
     * @return mixed
     * @throws \ReflectionException
     */
    public static function failed($message = '请求失败!~', $status_code = Response::HTTP_BAD_REQUEST, $data = [])
    {
        $time = time();
        empty($data) && $data = [];
        return static::getInstance()->response->array(compact('status_code', 'message', 'time', 'data'));
    }

}

