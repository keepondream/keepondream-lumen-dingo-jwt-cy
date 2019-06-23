<?php
/**
 * Description: 前端测试抽象基类
 * Author: WangSx
 * DateTime: 2019-06-23 15:19
 */

namespace Tests;

use App\Common\Helper;
use App\Http\Controllers\Frontend\V1\User\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Testing\TestCase;

abstract class FrontendTestCase extends TestCase
{

    /**
     * 当前登录用户的详情
     * @var null|array
     */
    protected $login_user = null;

    /**
     * 当前用户秘钥
     * @var null|array
     */
    protected $user_auth = null;

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
     * Description: 构建 api 用户共享基镜
     * Author: WangSx
     * DateTime: 2019-06-23 07:21
     */
    protected function setUp(): void
    {
        parent::setUp();
        try {
            if (is_null($this->login_user) || is_null($this->user_auth)) {
                $params = factory(User::class)->raw();
                $password = $params['password'];
                $params['password'] = Hash::make($password);
                $user = User::create($params);
                $login_user = Helper::fromUser($user, UserController::getService());
                if (!empty($login_user['access_token'])) {
                    $params['password'] = $password;
                    $this->login_user = array_merge($params, $login_user);
                    $this->user_auth = [
                        'HTTP_Authorization' => 'Bearer ' . $this->login_user['access_token']
                    ];
                } else {
                    throw new \Exception('phpunit construct failed , api user login failed .');
                }
            }

        } catch (\Exception $e) {
            Log::error('phpunit setUp failed : ' . $e->getTraceAsString());
        }

    }

    /**
     * Description: 释放基镜
     * Author: WangSx
     * DateTime: 2019-06-23 07:22
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->login_user = null;
        $this->user_auth = null;
    }

    /**
     * Description: 携带用户认证秘钥的接口请求
     * Author: WangSx
     * DateTime: 2019-06-23 14:53
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
        empty($server) && $server = $this->user_auth;

        return $this->call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

}


