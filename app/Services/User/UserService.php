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
            $expire = time() + ($this->getApiJwt()->factory()->getTTL() * 60);
            # 单点登录,更新Redis,token
            Helper::updateUserRedisToken($requestData['mobile'], $access_token, $this);

            $arr = compact('access_token', 'token_type', 'expire');
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
     * @return mixed
     */
    public function refreshToken()
    {
        $user = $this->getApiJwt()->user();
        $token = $this->getApiJwt()->refresh(true,true);
        if (!empty($token)) {
            # 单点登录,更新Redis
            Helper::updateUserRedisToken($user->mobile, $token, $this);
        }

        return $token;
    }

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
            $data['token'] = Helper::fromUser($user, $this);
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

}