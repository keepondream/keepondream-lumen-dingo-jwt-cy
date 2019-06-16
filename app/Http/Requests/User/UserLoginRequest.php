<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-16 23:38
 */

namespace App\Http\Requests\User;


use App\Http\Requests\Request;

class UserLoginRequest extends Request
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
            'password' => 'required|string'
        ];

        return $rules;
    }

    protected function getCustomAttributes()
    {
        return [
            'mobile' => '手机',
            'password' => '密码'
        ];
    }

}

