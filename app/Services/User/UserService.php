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

class UserService extends Service implements UserInterface
{
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
        $access_token = $this->getApiJwt()->attempt($requestData);
        if ($access_token) {
            $token_type = 'Bearer';
            $expire_time = time() + ($this->getApiJwt()->factory()->getTTL() * 60);
            # 单点登录,更新Redis,token
            Helper::updateUserRedisToken($requestData['mobile'], $access_token, $this);

            $arr = compact('access_token', 'token_type', 'expire_time');
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
        $data = $this->getApiJwt()->user()->toArray();

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
        $user = $this->getApiJwt()->user();
        $this->getApiJwt()->logout(true);
        # 清除前台用户的token
        $this->getRedis()->hdel(CONSTANT_RedisKey::AUTH_USER_TOKEN, $user->mobile);

        return true;
    }

    /**
     * Description: 刷新token
     * Author: WangSx
     * DateTime: 2019-06-18 14:13
     * @return array
     */
    public function refreshToken()
    {
        $arr = [];
        $user = $this->getApiJwt()->user();
        $access_token = $this->getApiJwt()->refresh(true, true);
        if (!empty($access_token)) {
            $token_type = 'Bearer';
            $expire_time = time() + ($this->getApiJwt()->factory()->getTTL() * 60);
            # 单点登录,更新Redis
            Helper::updateUserRedisToken($user->mobile, $access_token, $this);
            $arr = compact('access_token', 'token_type', 'expire_time');
        }

        return $arr;
    }

    /**
     * Description: 创建用户
     * Author: WangSx
     * DateTime: 2019-06-18 14:35
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {
        $arr = [];
        $user = User::create($data);

        if ($user) {
            # 生成前台api用户token
            $arr = Helper::fromUser($user, $this);
        }

        return $arr;
    }

    public function update(array $data, int $id)
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

}