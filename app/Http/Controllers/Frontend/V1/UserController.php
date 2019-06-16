<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-16 15:58
 */

namespace App\Http\Controllers\Frontend\V1;


use App\Http\Controllers\Controller;
use App\Models\User;
use Dingo\Api\Auth\Auth;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;

class UserController extends Controller
{
    protected $jwt;

    /**
     * UserController constructor.
     * @param JWTAuth $jwt
     */
    public function __construct(JWTAuth $jwt)
    {
        parent::__construct($jwt);

        $this->middleware('auth:api', ['except' => [
            'login',
        ]]);
    }

    public function login(Request $request)
    {
        $a = $request->only('mobile', 'password');
        if (!$token = $this->jwt->attempt($a)) {
            return response()->json(['user_not_found'], 404);
        }



        return response()->json(compact('token'));
    }

    public function create(Request $request)
    {
        $params = $request->input();
        if (!empty($params['password'])) {
            $params['password'] = Hash::make($params['password']);
        }

        User::create($params);

        $this->response->noContent();
    }

    public function getUser(Request $request) {

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
        $this->jwt->user();
        $this->jwt->setToken($token)->invalidate();

        return $this->response->noContent();
    }
}