<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-22 16:03
 */

namespace App\Listeners;


use App\Common\Constants\CONSTANT_Queue_RedisKey;
use App\Events\AdminUserLogEvent;
use App\Models\AdminUserLog;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminUserLogListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * 任务应该发送到的队列的连接的名称
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * 任务应该发送到的队列的名称
     *
     * @var string|null
     */
    public $queue = CONSTANT_Queue_RedisKey::ADMIN_USER_LOG_QUEUE;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 1;

    /**
     * 超时时间。
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Author: WangSx
     * DateTime: 2019-06-22 16:10
     * @param AdminUserLogEvent $event
     * @return bool
     */
    public function handle(AdminUserLogEvent $event)
    {
        $admin_user_id = $event->admin_user_id;
        $comment = $event->comment;
        AdminUserLog::create(compact('admin_user_id', 'comment'));

        return true;
    }
}