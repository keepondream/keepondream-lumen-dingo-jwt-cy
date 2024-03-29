<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-16 21:49
 */

namespace App\Http\Controllers\Backend\V1\AdminUser;


use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUser\AdminUserCreateRequest;
use App\Http\Requests\AdminUser\AdminUserLoginRequest;
use App\Services\AdminUser\AdminUserService;
use App\Services\ServiceManager;

class AdminUserController extends Controller
{
    /**
     * Description: 管理员登录
     * Author: WangSx
     * DateTime: 2019-06-22 15:06
     * @param AdminUserLoginRequest $request
     * @return mixed
     * @throws \ReflectionException
     */
    public function login(AdminUserLoginRequest $request)
    {
        $requestData = $request->only('mobile', 'password');
        $data = self::getService()->login($requestData);
        return success($data);
    }

    /**
     * Description: 创建管理员
     * Author: WangSx
     * DateTime: 2019-06-22 15:10
     * @param AdminUserCreateRequest $request
     * @return mixed
     * @throws \ReflectionException
     */
    public function create(AdminUserCreateRequest $request)
    {
        $data = self::getService()->create($request->getValidateParams());

        return success($data);
    }

    /**
     * Author: WangSx
     * DateTime: 2019-06-21 14:28
     * @return mixed
     * @throws \ReflectionException
     */
    public function me()
    {
        $data = self::getService()->detail();

        return success($data);
    }

    /**
     * Description: refresh
     * Author: WangSx
     * DateTime: 2019-06-23 16:29
     * @return mixed
     * @throws \ReflectionException
     */
    public function refresh()
    {
        $token = self::getService()->refreshToken();
        return success($token);
    }

    /**
     * Description: 退出
     * Author: WangSx
     * DateTime: 2019-06-21 17:58
     * @return mixed
     * @throws \ReflectionException
     */
    public function logout()
    {
        self::getService()->logout();

        return success('', '退出成功!');
    }

    /**
     * Author: WangSx
     * DateTime: 2019-06-21 14:24
     * @return AdminUserService
     * @throws \ReflectionException
     */
    public static function getService(): AdminUserService
    {
        return ServiceManager::getInstance()->adminUserService(AdminUserService::class);
    }
}