<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Common\Constants\CONSTANT_User;

class AddUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('mobile', 11)->nullable()->unique()->comment('手机号');
            $table->string('nick_name', 30)->default('')->comment('昵称');
            $table->string('password')->default('')->comment('密码');
            $table->tinyInteger('is_black')->default(CONSTANT_User::USER_BLACK_OFF)->comment('黑名单开关 1: 开  2: 关');
            $table->unsignedInteger('last_login_time')->nullable()->comment('最后登录时间');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `users` COMMENT='用户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
