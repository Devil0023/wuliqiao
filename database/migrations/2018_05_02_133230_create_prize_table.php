<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prize', function (Blueprint $table) {
            $table->increments('id');
            $table->string('prize')->comment('奖品名称');
            $table->string('img')->comment('奖品图片')->nullable();
            $table->integer('cost')->comment('消耗积分');
            $table->string('intro')->comment('奖品简介');
            $table->string('num')->comment('数量');
            $table->timestamp('stime')->default('1970-01-01 08:00:01')->comment('兑换开始时间');
            $table->timestamp('etime')->default('1970-01-01 08:00:01')->comment('兑换结束时间');
            $table->tinyInteger('checked')->index()->comment('是否上线');
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
        Schema::dropIfExists('prize');
    }
}
