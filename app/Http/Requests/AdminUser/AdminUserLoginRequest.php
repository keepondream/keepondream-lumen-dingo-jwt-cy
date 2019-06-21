<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-21 14:00
 */

namespace App\Http\Requests\AdminUser;


use App\Http\Requests\Request;

class AdminUserLoginRequest extends Request
{

    /**
     * Description: params
     * Author: WangSx
     * DateTime: 2019-06-21 14:13
     * @return \Dingo\Api\Http\Request
     */
    public function getValidateParams()
    {
        $params = $this->all();

        return $params;
    }

    /**
     * Description: rules
     * Author: WangSx
     * DateTime: 2019-06-21 14:13
     * @return array
     */
    protected function getValidateRules()
    {
        $rules = [
            'mobile' => 'required|int',
            'password' => 'required|string',
            'nick_name' => 'string'
        ];

        return $rules;
    }

    /**
     * Description: custom
     * Author: WangSx
     * DateTime: 2019-06-21 14:13
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