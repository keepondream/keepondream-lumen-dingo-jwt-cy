<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-22 16:36
 */

namespace App\Common\Constants;


class CONSTANT_Queue_RedisKey
{
    # 管理员日志队列
    const ADMIN_USER_LOG_QUEUE = 'admin_user_log_queue';
    # 用户注册任务队列
    const USER_LOGIN_QUEUE = 'user_login_queue';
}