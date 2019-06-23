<?php

use App\Common\Constants\CONSTANT_Behavior;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBehaviorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50)->default('')->comment('货币名称');
            $table->string('en_name', 100)->default('')->comment('货币en名称');
            $table->string('short_name', 20)->default('')->comment('货币简称');
            $table->string('sign')->default('')->comment('货币标识');
            $table->string('expand_json')->default('')->comment('扩展json字段: 该货币发币配置项等');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `currencies` COMMENT='货币表'");

        Schema::create('user_currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('currency_id')->comment('货币ID')->index();
            $table->unsignedInteger('user_id')->comment('用户ID')->index();
            $table->unsignedInteger('currency_current_num')->default(0)->comment('用户货币当前总数量');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `user_currencies` COMMENT='用户货币数量表'");


        Schema::create('behaviors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sign', 20)->default('')->comment('行为标识');
            $table->tinyInteger('type')->default(CONSTANT_Behavior::TYPE_REWARD)->comment('类型 1:奖励 , 2:惩罚');
            $table->unsignedInteger('num')->default(0)->comment('货币数量');
            $table->unsignedInteger('currency_id')->default(0)->comment('货币ID, 所属货币')->index();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `behaviors` COMMENT='行为表'");


        Schema::create('behavior_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('behavior_id')->default(0)->comment('行为ID')->index();
            $table->tinyInteger('type')->comment('行为类型 1:奖励 , 2:惩罚;拓展:用于以后根据类型统计');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID')->index();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `behavior_logs` COMMENT='行为记录表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('user_currencies');
        Schema::dropIfExists('behaviors');
        Schema::dropIfExists('behavior_logs');
    }
}
