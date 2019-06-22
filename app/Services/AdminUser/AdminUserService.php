<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-20 18:23
 */

namespace App\Services\AdminUser;


use App\Common\Constants\CONSTANT_RedisKey;
use App\Common\Helper;
use App\Models\AdminUser;
use App\Services\ConstructInterfaces\AdminUser\AdminUserInterface;
use App\Services\Service;

class AdminUserService extends Service implements AdminUserInterface
{
    /**
     * Description: 登录
     * Author: WangSx
     * DateTime: 2019-06-22 15:18
     * @param $requestData
     * @return array
     */
    public function login($requestData)
    {
        $arr = [];
        $access_token = $this->getBackendJwt()->attempt($requestData);
        if ($access_token) {
            $token_type = 'Bearer';
            $expire = time() + ($this->getBackendJwt()->factory()->getTTL() * 60);
            # 单点登录,更新Redis,token
            Helper::updateAdminUserRedisToken($requestData['mobile'], $access_token, $this);

            $arr = compact('access_token', 'token_type', 'expire');
        }

        return $arr;

    }

    /**
     * Description: 详情
     * Author: WangSx
     * DateTime: 2019-06-22 15:18
     * @return mixed
     */
    public function detail()
    {

        $data = $this->getBackendJwt()->user()->toArray();

        return $data;
    }

    /**
     * Description: 退出
     * Author: WangSx
     * DateTime: 2019-06-22 15:18
     * @return bool
     */
    public function logout()
    {
        $user = $this->getBackendJwt()->user();
        $this->getBackendJwt()->logout(true);
        # 清除前台用户的token
        $this->getRedis()->hdel(CONSTANT_RedisKey::AUTH_ADMIN_USER_TOKEN, $user->mobile);

        return true;
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
     * Description: 创建管理员
     * Author: WangSx
     * DateTime: 2019-06-22 15:18
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return AdminUser::create($data);
    }
}