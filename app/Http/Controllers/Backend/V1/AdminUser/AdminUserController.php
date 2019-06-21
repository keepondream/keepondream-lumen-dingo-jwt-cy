<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-16 21:49
 */

namespace App\Http\Controllers\Backend\V1\AdminUser;


use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUser\AdminUserLoginRequest;
use App\Services\AdminUser\AdminUserService;
use App\Services\ServiceManager;
use Dingo\Api\Http\Request;

class AdminUserController extends Controller
{
    public function login(AdminUserLoginRequest $request)
    {
        $requestData = $request->only('mobile', 'password');
        if (!$data = self::getService()->login($requestData)) {
            return failed('账号或密码错误!~');
        }

        return success($data);
    }


    public function create(Request $request)
    {
        $data = self::getService()->detail();

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