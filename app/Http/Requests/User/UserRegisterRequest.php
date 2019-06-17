<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-17 10:01
 */

namespace App\Http\Requests\User;


use App\Http\Requests\Request;

class UserRegisterRequest extends Request
{
    protected function getValidateParams()
    {
        $params = $this->all();

        return $params;
    }

    protected function getValidateRules()
    {
        $rules = [
            'mobile' => 'required|int',
            'password' => 'required|string',
            'nick_name' => 'required|string'
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