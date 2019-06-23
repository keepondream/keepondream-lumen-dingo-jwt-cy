<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-22 15:07
 */

namespace App\Http\Requests\AdminUser;


use App\Http\Requests\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserCreateRequest extends Request
{

    public function getValidateParams()
    {
        $params = $this->all();
        $params['password'] = Hash::make($params['password']);

        return $params;
    }

    protected function getValidateRules()
    {

        $rules = [
            'mobile' => 'required|int',
            'password' => 'required|string',
            'nick_name' => 'string'
        ];

        return $rules;
    }

    protected function getCustomAttributes()
    {
        return [
            'mobile' => '手机号',
            'password' => '密码',
            'nick_name' => '昵称'
        ];
    }
}