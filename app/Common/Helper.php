<?php
/**
 * Description: 全局通用辅助函数
 * Author: WangSx
 * DateTime: 2019-06-18 15:05
 */

namespace App\Common;


use App\Common\BaseClasses\ResourceManager;
use App\Common\Constants\CONSTANT_RedisKey;
use App\Models\User;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Facades\JWTAuth;

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
            # 捕获过期令牌,防止程序中断
            try {
                JWTAuth::setToken($oldToken)->invalidate();
            } catch (\Exception $e) {
                Log::debug('单点登录,token过期 ' . $e->getMessage());
            }
        }
        # 设置新token
        $redis->hset(CONSTANT_RedisKey::AUTH_USER_TOKEN, $mobile, $token);
    }

    /**
     * Description: 200 正确响应 成功 公共方法
     * 与common.php 中的success函数一致
     * Author: WangSx
     * DateTime: 2019-06-19 15:06
     * @param array $data 响应数据数组
     * @param string $msg 响应消息
     * @param int $code 响应编码
     * @return mixed
     * @throws \ReflectionException
     */
    public static function success($data = [], $msg = '请求成功!', $code = Response::HTTP_OK)
    {
        return static::getInstance()->response->array(compact('code', 'msg', 'data'));
    }

    /**
     * Description: 200 正确响应 失败 公共方法
     * 与common.php 中的 failed 函数一致
     * Author: WangSx
     * DateTime: 2019-06-19 15:12
     * @param string $msg 响应消息
     * @param int $code 响应编码
     * @param array $data 响应数据数组
     * @return mixed
     * @throws \ReflectionException
     */
    public static function failed($msg = '请求失败!~', $code = Response::HTTP_BAD_REQUEST, $data = [])
    {
        return static::getInstance()->response->array(compact('code', 'msg', 'data'));
    }
}

