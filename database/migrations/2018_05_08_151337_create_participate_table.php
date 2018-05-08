<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participate', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->index()->comment('用户id');
            $table->string('openid')->index()->comment('openid');
            $table->tinyInteger('participate')->comment('是否报名');
            $table->tinyInteger('sign')->comment('是否签到');
            $table->timestamp('participatetime')->default('1970-01-01 08:00:01')->comment('报名时间');
            $table->timestamp('signtime')->default('1970-01-01 08:00:01')->comment('签到时间');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participate');
    }
}
