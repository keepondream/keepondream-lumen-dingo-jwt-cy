<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-18 11:10
 */

namespace App\Services\User;


use App\Common\Constants\CONSTANT_RedisKey;
use App\Common\Helper;
use App\Models\User;
use App\Services\ConstructInterfaces\User\UserInterface;
use App\Services\Service;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService extends Service implements UserInterface
{
    /**
     * Description: 创建用户
     * Author: WangSx
     * DateTime: 2019-06-18 14:35
     * @param array $data
     * @return mixed|void
     */
    public function create(array $data)
    {
        $user = User::create($data);

        if ($user) {
            # 生成前台api用户token
            $data['token'] = Helper::fromUser($user);
        }

        return $data;
    }

    public function update(array $data)
    {
        // TODO: Implement update() method.
    }

    public function getById(int $id)
    {
        // TODO: Implement getById() method.
    }

    public function deleteById(int $id)
    {
        // TODO: Implement deleteById() method.
    }

    /**
     * Description: 登录
     * Author: WangSx
     * DateTime: 2019-06-18 13:00
     * @param $requestData
     * @return array
     */
    public function login($requestData)
    {
        $arr = [];
        $token = JWTAuth::attempt($requestData);

        # 单点登录,更新Redis,token
        Helper::updateUserRedisToken($requestData['mobile'], $token);

        if ($token) {
            $arr = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'ttl_time' => time() + (JWTAuth::factory()->getTTL() * 60),
                'time' => time()
            ];
        }

        return $arr;
    }

    /**
     * Description: 获取当前用户信息
     * Author: WangSx
     * DateTime: 2019-06-18 13:53
     * @return array
     */
    public function detail()
    {
        $data = JWTAuth::parseToken()->toUser()->toArray();

        return $data;
    }

    /**
     * Description: 退出
     * Author: WangSx
     * DateTime: 2019-06-18 14:04
     * @return bool
     */
    public function logout()
    {
        $user = jwtauth::parsetoken()->touser();
        JWTAuth::parseToken()->invalidate();

        # 清除前台用户的token
        $this->redis->hdel(CONSTANT_RedisKey::AUTH_USER_TOKEN, $user->mobile);

        return true;
    }

    /**
     * Description: 刷新token
     * Author: WangSx
     * DateTime: 2019-06-18 14:13
     * @return mixed
     */
    public function refreshToken()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::parseToken()->refresh();
        # 单点登录,更新Redis
        Helper::updateUserRedisToken($user->mobile, $token);

        return $token;
    }


}