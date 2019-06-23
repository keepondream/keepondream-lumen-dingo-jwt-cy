<?php
/**
 * Description: 管理员模块测试
 * Author: WangSx
 * DateTime: 2019-06-23 15:34
 */

namespace Tests\Feature\Backend\V1\AdminUser;


use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\BackendTestCase;

class AdminControllerTest extends BackendTestCase
{
    use DatabaseTransactions;

    /**
     * Description: 管理员登录
     * Author: WangSx
     * DateTime: 2019-06-23 16:16
     */
    public function testLogin()
    {
        $params = factory(AdminUser::class)->raw();
        $password = $params['password'];
        $params['password'] = Hash::make($password);
        if ($admin_user = AdminUser::create($params)) {
            $params['password'] = $password;
        }
        $response = $this->loginCall('POST', '/api/b/login', $params);

        $res = $response->getOriginalContent();
        $this->assertEquals(200, $response->status());
        $this->assertEquals(200, $res['status_code']);
        $this->assertArrayHasKey('access_token', $res['data']);
    }

    /**
     * Description: 添加管理员
     * Author: WangSx
     * DateTime: 2019-06-23 16:10
     */
    public function testCreate()
    {
        $params = factory(AdminUser::class)->raw();
        $response = $this->loginCall('POST', '/api/b/create', $params);

        $res = $response->getOriginalContent();
        $this->assertEquals(200, $response->status());
        $this->assertEquals(200, $res['status_code']);
        $this->assertGreaterThan(0, $res['data']['id']);
        $this->seeInDatabase((new AdminUser())->getTable(), ['mobile' => $params['mobile']]);
    }

    /**
     * Description: 管理员详情
     * Author: WangSx
     * DateTime: 2019-06-23 16:18
     */
    public function testMe()
    {
        $response = $this->loginCall('GET', '/api/b/me');

        $res = $response->getOriginalContent();
        $this->assertEquals(200, $response->status());
        $this->assertEquals(200, $res['status_code']);
        $this->assertGreaterThan(0, $res['data']['id']);
    }

    /**
     * Description: 刷新token
     * Author: WangSx
     * DateTime: 2019-06-23 16:32
     */
    public function testRefresh()
    {
        $response = $this->loginCall('GET', '/api/b/refresh');
        $res = $response->getOriginalContent();

        $this->assertEquals(200, $response->status());
        $this->assertEquals(200, $res['status_code']);
        $this->assertArrayHasKey('access_token', $res['data']);
    }

    /**
     * Description: 退出
     * Author: WangSx
     * DateTime: 2019-06-23 16:34
     */
    public function testLogout()
    {
        $response = $this->loginCall('GET', '/api/b/logout');
        $res = $response->getOriginalContent();

        $this->assertEquals(200, $response->status());
        $this->assertEquals(200, $res['status_code']);
    }
}