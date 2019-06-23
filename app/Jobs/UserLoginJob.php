<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-23 21:54
 */

namespace App\Jobs;


use App\Common\Constants\CONSTANT_Queue_RedisKey;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserLoginJob extends Job
{

    /**
     * 任务最大尝试次数。
     *
     * @var int
     */
    public $tries = 5;

    /**
     * 任务运行的超时时间。
     *
     * @var int
     */
    public $timeout = 300; //秒数

    /**
     * @var User
     */
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->onQueue(CONSTANT_Queue_RedisKey::USER_LOGIN_QUEUE);
    }


    public function handle()
    {
        Log::debug('用户登录任务触发');

        return true;
    }
}
