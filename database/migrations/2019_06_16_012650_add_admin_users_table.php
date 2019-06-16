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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
