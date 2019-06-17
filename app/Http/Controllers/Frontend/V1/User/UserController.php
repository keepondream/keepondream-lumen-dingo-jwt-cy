<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-16 21:40
 */

namespace App\Http\Controllers\Frontend\V1\User;


use App\Common\Constants\CONSTANT_RedisKey;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Models\User;
use Dingo\Api\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;

class UserController extends Controller
{
    /**
     * Description: Get a JWT via given credentials.
     * Author: WangSx
     * DateTime: 2019-06-17 15:00
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only('mobile', 'password');

        if (! $token = $this->jwt->attempt($credentials)) {

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Description: Get the authenticated User.
     * Author: WangSx
     * DateTime: 2019-06-17 15:01
     * @return \Illuminate\Http\JsonResponse
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function me()
    {
        $user = $this->jwt->parseToken()->toUser();
        return $this->respondWithToken('sssssssss');
        return response()->json($user);
    }

    /**
     * Description: Log the user out (Invalidate the token).
     * Author: WangSx
     * DateTime: 2019-06-17 15:01
     * @return \Illuminate\Http\JsonResponse
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function logout()
    {
        $this->jwt->invalidate();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Description: Refresh a token.
     * Author: WangSx
     * DateTime: 2019-06-17 15:02
     * @return \Illuminate\Http\JsonResponse
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function refresh()
    {
        $token = $this->jwt->parseToken()->refresh();
        return $this->respondWithToken($token);
    }

    public function create(UserRegisterRequest $request)
    {
        $params = $request->all();
        $params['password'] = Hash::make($params['password']);
        $user = User::create($params);

        $token = $this->jwt->fromUser($user);

        return $this->respondWithToken($token);
    }

    /**
     * Description: Get the token array structure.
     * Author: WangSx
     * DateTime: 2019-06-17 15:03
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $arr = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->jwt->factory()->getTTL() * 60,
            'ttl_time' => time() + ($this->jwt->factory()->getTTL() * 60),
            'time' => time()
        ];

        return $this->response->array($arr);
    }
}