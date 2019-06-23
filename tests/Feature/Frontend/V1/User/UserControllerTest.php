<?php
/**
 * Description: 前端用户接口测试
 * Author: WangSx
 * DateTime: 2019-06-22 21:21
 */

namespace Tests\Feature\Frontend\V1\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\FrontendTestCase;

class UserControllerTest extends FrontendTestCase
{
    use DatabaseTransactions;

    /**
     * Description: 测试登录
     * Author: WangSx
     * DateTime: 2019-06-22 22:15
     */
    public function testLogin()
    {
        $params = factory(User::class)->raw();
        $password = $params['password'];
        $params['password'] = Hash::make($password);
        if (User::create($params)) {
            $params['password'] = $password;
        }
        $response = $this->call('POST', '/api/f/login', $params);

        $res = $response->getOriginalContent();
        $this->assertEquals(200, $response->status());
        $this->assertEquals(200, $res['status_code']);
        $this->assertArrayHasKey('access_token', $res['data']);
    }

    /**
     * Description: 获取用户详情
     * Author: WangSx
     * DateTime: 2019-06-23 08:33
     */
    public function testMe()
    {
        $response = $this->loginCall('GET', '/api/f/me');
        $res = $response->getOriginalContent();

        $this->assertEquals(200, $response->status());
        $this->assertEquals(200, $res['status_code']);
        $this->assertGreaterThan(0, $res['data']['id']);
    }

    /**
     * Description: 刷新token
     * Author: WangSx
     * DateTime: 2019-06-23 08:46
     */
    public function testRefresh()
    {
        $response = $this->loginCall('GET', '/api/f/refresh');
        $res = $response->getOriginalContent();

        $this->assertEquals(200, $response->status());
        $this->assertEquals(200, $res['status_code']);
        $this->assertArrayHasKey('access_token', $res['data']);
    }

    /**
     * Description: 退出
     * Author: WangSx
     * DateTime: 2019-06-23 08:47
     */
    public function testLogout()
    {
        $response = $this->loginCall('GET', '/api/f/logout');
        $res = $response->getOriginalContent();

        $this->assertEquals(200, $response->status());
        $this->assertEquals(200, $res['status_code']);
    }

}

