<?php
/**
 * Description: 全局通用辅助函数
 * Author: WangSx
 * DateTime: 2019-06-18 15:05
 */

namespace App\Common;


use App\Common\Constants\CONSTANT_RedisKey;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Facades\JWTAuth;

class Helper
{
    /**
     * Description: 生成前台api用户token并单点登录存储,
     * Author: WangSx
     * DateTime: 2019-06-18 16:45
     * @param User $user
     * @return mixed
     */
    public static function fromUser(User $user)
    {
        $token = JWTAuth::fromUser($user);

        if (!empty($token)) {
            Helper::updateUserRedisToken($user->mobile, $token);
        }

        return $token;
    }

    /**
     * Description: 单点登录,用于记录用户token,当token变更后使旧token加入黑名单
     * Author: WangSx
     * DateTime: 2019-06-18 16:49
     * @param int $mobile api用户手机号
     * @param string $token api用户新token
     */
    public static function updateUserRedisToken(int $mobile, string $token)
    {
        $redis = Redis::connection();
        # 将旧token 加入黑名单,使其登录失效
        if ($oldToken = $redis->hget(CONSTANT_RedisKey::AUTH_USER_TOKEN, $mobile)) {
            JWTAuth::setToken($oldToken)->invalidate();
        }
        # 设置新token
        $redis->hset(CONSTANT_RedisKey::AUTH_USER_TOKEN, $mobile, $token);
    }

}