<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-16 21:40
 */

namespace App\Http\Controllers\Frontend\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Services\ServiceManager;
use App\Services\User\UserService;

class UserController extends Controller
{
    /**
     * Description: 登录
     * Author: WangSx
     * DateTime: 2019-06-18 12:41
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     */
    public function login(UserLoginRequest $request)
    {
        $requestData = $request->only('mobile', 'password');

        if (!$data = self::getService()->login($requestData)) {
            $this->response()->errorMethodNotAllowed();
        }

        return $this->response->array($data);
    }

    /**
     * Description: 获取用户详情
     * Author: WangSx
     * DateTime: 2019-06-18 14:00
     * @return mixed
     * @throws \ReflectionException
     */
    public function me()
    {
        $data = self::getService()->detail();

        return $this->response->array($data);
    }

    /**
     * Description: 退出
     * Author: WangSx
     * DateTime: 2019-06-18 14:05
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     */
    public function logout()
    {
        self::getService()->logout();

        return $this->response->array(['message' => 'Successfully logged out']);
    }

    /**
     * Description: 刷新token接口  ;  自动无痛刷新在中间件里的header中返回
     * Author: WangSx
     * DateTime: 2019-06-18 14:21
     * @return \Dingo\Api\Http\Response
     * @throws \ReflectionException
     */
    public function refresh()
    {
        $token = self::getService()->refreshToken();

        if ($token) {
            return $this->response()->array(compact('token'));
        } else {
            return $this->response->noContent();
        }

    }

    /**
     * Description: 注册
     * Author: WangSx
     * DateTime: 2019-06-18 14:31
     * @param UserRegisterRequest $request
     * @return mixed
     * @throws \ReflectionException
     */
    public function register(UserRegisterRequest $request)
    {
        $requestData = $request->getValidateParams();
        $data = self::getService()->create($requestData);

        if ($data) {
            return $this->response->array($data);
        }

        return $this->response->array(['msg' => '创建失败!~']);
    }

    /**
     * Description:
     * Author: WangSx
     * DateTime: 2019-06-18 12:33
     * @return UserService
     * @throws \ReflectionException
     */
    public static function getService(): UserService
    {
        return ServiceManager::getInstance()->userService(UserService::class);
    }
}