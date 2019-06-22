<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Common\Constants\CONSTANT_AdminUser;

class AddAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('mobile', 11)->nullable()->unique()->comment('手机号');
            $table->string('nick_name', 30)->default('')->comment('昵称');
            $table->string('password')->default('')->comment('密码');
            $table->tinyInteger('is_black')->default(CONSTANT_AdminUser::ADMIN_USER_BLACK_OFF)->comment('黑名单 1: 是  2: 否');
            $table->unsignedInteger('last_login_time')->nullable()->comment('最后登录时间');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `admin_users` COMMENT='管理员表'");

        Schema::create('admin_user_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_user_id')->comment('管理员ID');
            $table->string('comment')->comment('日志描述');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `admin_user_logs` COMMENT='管理员日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
        Schema::dropIfExists('admin_user_logs');
    }
}
