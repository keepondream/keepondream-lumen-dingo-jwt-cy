<?php
/**
 * Description: 后台测试抽象基类
 * Author: WangSx
 * DateTime: 2019-06-23 15:19
 */

namespace Tests;

use App\Common\Helper;
use App\Http\Controllers\Backend\V1\AdminUser\AdminUserController;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Testing\TestCase;

abstract class BackendTestCase extends TestCase
{
    /**
     * 当前登录用户详情
     * @var null|array
     */
    protected $admin_login_user = null;

    /**
     * 当前用户秘钥
     * @var null|array
     */
    protected $admin_user_auth = null;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    /**
     * Description: 构建 backend 用户共享基镜
     * Author: WangSx
     * DateTime: 2019-06-23 15:30
     */
    protected function setUp(): void
    {
        parent::setUp();
        try {
            if (is_null($this->admin_login_user) || is_null($this->admin_user_auth)) {
                $params = factory(AdminUser::class)->raw();
                $password = $params['password'];
                $params['password'] = Hash::make($password);
                $admin_user = AdminUser::create($params);
                $login_admin_user = Helper::fromAdminUser($admin_user, AdminUserController::getService());
                if (!empty($login_admin_user['access_token'])) {
                    $params['password'] = $password;
                    $this->admin_login_user = array_merge($params, $login_admin_user);
                    $this->admin_user_auth = [
                        'HTTP_Authorization' => 'Bearer ' . $this->admin_login_user['access_token']
                    ];
                } else {
                    throw new \Exception('phpunit construct failed , admin user login failed .');
                }
            }
        } catch (\Exception $e) {
            Log::error('phpunit setUp failed : ' . $e->getTraceAsString());
        }
    }

    /**
     * Description: 释放基镜
     * Author: WangSx
     * DateTime: 2019-06-23 15:31
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->admin_login_user = null;
        $this->admin_user_auth = null;
    }

    /**
     * Description: 携带用户认证秘钥的接口请求
     * Author: WangSx
     * DateTime: 2019-06-23 15:32
     * @param  string $method
     * @param  string $uri
     * @param  array $parameters
     * @param  array $cookies
     * @param  array $files
     * @param  array $server
     * @param  string $content
     * @return \Illuminate\Http\Response
     */
    public function loginCall($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        empty($server) && $server = $this->admin_user_auth;

        return $this->call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

}