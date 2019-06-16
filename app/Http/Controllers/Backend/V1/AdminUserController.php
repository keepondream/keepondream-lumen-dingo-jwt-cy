<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-16 16:04
 */

namespace App\Http\Controllers\Backend\V1;


use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;

class AdminUserController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
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
        AdminUser::create($params);
        $this->response->noContent();
    }

}