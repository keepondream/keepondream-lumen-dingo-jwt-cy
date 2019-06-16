<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-16 21:49
 */

namespace App\Http\Controllers\Backend\V1\AdminUser;


use App\Common\Constants\CONSTANT_RedisKey;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Dingo\Api\Http\Request;

class AdminUserController extends Controller
{
    public function login(Request $request)
    {
        $params = $request->only('mobile', 'password');
        if (!$token = $this->jwt->attempt($params)) {
            return response()->json(['user_not_found'], 404);
        }
        $mobile = $params['mobile'];
        // TODO: 先写,之后封services更改
        # 更新用户token
        $oldToken = $this->redis->hget(CONSTANT_RedisKey::AUTH_ADMIN_USER_TOKEN, $mobile);

        if (!empty($oldToken) && $oldToken != $token) {
            $this->jwt->setToken($oldToken)->toUser();
            $this->jwt->setToken($oldToken)->invalidate();
        }

        $this->redis->hset(CONSTANT_RedisKey::AUTH_ADMIN_USER_TOKEN, $mobile, $token);


        return response()->json(compact('token'));
    }



    public function create(Request $request)
    {
        $params = $request->input();
        if (!empty($params['password'])) {
            $params['password'] = Hash::make($params['password']);
        }

        AdminUser::create($params);

        $this->response->noContent();
    }

    public function getUser(Request $request)
    {

//        $token = $this->jwt->getToken();
//        $this->jwt->user();
//        $data = $this->jwt->setToken($token)->toUser();
//        print_r($data);

        $user = $this->jwt->user();
        return $this->response->array(compact('user'));
    }

    public function logout()
    {
        $token = $this->jwt->getToken();
        $user = $this->jwt->user();
        $mobile = $user->mobile;
        $this->jwt->setToken($token)->invalidate();
        $this->redis->hdel(CONSTANT_RedisKey::AUTH_ADMIN_USER_TOKEN, $mobile);

        return $this->response->noContent();
    }
}