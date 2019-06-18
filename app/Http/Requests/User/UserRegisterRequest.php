<?php
/**
 * Description: api用户注册验证器
 * Author: WangSx
 * DateTime: 2019-06-17 10:01
 */

namespace App\Http\Requests\User;


use App\Http\Requests\Request;
use Illuminate\Support\Facades\Hash;

class UserRegisterRequest extends Request
{
    /**
     * Author: WangSx
     * DateTime: 2019-06-18 17:41
     * @return \Dingo\Api\Http\Request
     */
    public function getValidateParams()
    {
        $params = $this->all();
        $params['password'] = Hash::make($params['password']);

        return $params;
    }

    /**
     * Author: WangSx
     * DateTime: 2019-06-18 17:41
     * @return array
     */
    protected function getValidateRules()
    {
        $rules = [
            'mobile' => 'required|int',
            'password' => 'required|string',
            'nick_name' => 'required|string'
        ];

        return $rules;
    }

    /**
     * Author: WangSx
     * DateTime: 2019-06-18 17:42
     * @return array
     */
    protected function getCustomAttributes()
    {
        return [
            'mobile' => '手机号',
            'password' => '密码',
            'nick_name' => '昵称'
        ];
    }
}