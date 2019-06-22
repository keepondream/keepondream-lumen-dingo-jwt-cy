<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-22 15:58
 */

namespace App\Events;


class AdminUserLogEvent extends Event
{
    /**
     * @var int
     */
    public $admin_user_id;

    /**
     * @var string
     */
    public $comment;

    /**
     * AdminUserLogEvent constructor.
     * @param int $admin_user_id
     * @param string $comment
     */
    public function __construct(int $admin_user_id,string $comment)
    {
        $this->admin_user_id = $admin_user_id;
        $this->comment = $comment;
    }
}