<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-20 18:23
 */

namespace App\Services\AdminUser;


use App\Common\Constants\CONSTANT_RedisKey;
use App\Common\Helper;
use App\Events\AdminUserLogEvent;
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

            $admin_user = $this->getBackendJwt()->user();
            $admin_user->last_login_time = time();
            $admin_user->save();
            event(new AdminUserLogEvent($admin_user->id, '登录系统'));
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
     * Description: refresh token
     * Author: WangSx
     * DateTime: 2019-06-23 16:27
     * @return array
     */
    public function refreshToken()
    {
        $arr = [];
        $admin_user = $this->getBackendJwt()->user();
        $access_token = $this->getBackendJwt()->refresh(true, true);
        if (!empty($access_token)) {
            $token_type = 'Bearer';
            $expire_time = time() + ($this->getApiJwt()->factory()->getTTL() * 60);
            # 单点
            Helper::updateAdminUserRedisToken($admin_user->mobile, $access_token, $this);

            $arr = compact('access_token', 'token_type', 'expire_time');
        }

        return $arr;
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
        if ($res = AdminUser::create($data)) {
            return $res->toArray();
        }
        return [];
    }
}