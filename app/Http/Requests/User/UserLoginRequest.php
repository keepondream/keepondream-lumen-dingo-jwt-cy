<?php
/**
 * Description: 用户登录验证器
 * Author: WangSx
 * DateTime: 2019-06-16 23:38
 */

namespace App\Http\Requests\User;


use App\Http\Requests\Request;

class UserLoginRequest extends Request
{
    /**
     * Author: WangSx
     * DateTime: 2019-06-18 17:42
     * @return \Dingo\Api\Http\Request
     */
    public function getValidateParams()
    {
        $params = $this->all();

        return $params;
    }

    /**
     * Author: WangSx
     * DateTime: 2019-06-18 17:42
     * @return array
     */
    protected function getValidateRules()
    {
        $rules = [
            'mobile' => 'required|int',
            'password' => 'required|string'
        ];

        return $rules;
    }

    /**
     * Author: WangSx
     * DateTime: 2019-06-18 17:43
     * @return array
     */
    protected function getCustomAttributes()
    {
        return [
            'mobile' => '手机',
            'password' => '密码'
        ];
    }

}

