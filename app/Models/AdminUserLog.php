<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-22 15:52
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AdminUserLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_user_id', 'comment'
    ];

    /**
     * Description: 日志所属管理员
     * Author: WangSx
     * DateTime: 2019-06-22 18:21
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function adminUser()
    {
        return $this->hasOne(AdminUser::class, 'id','admin_user_id');
    }
}