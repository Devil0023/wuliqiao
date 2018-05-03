<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('标题名称');
            $table->timestamp('stime')->index()->default('1970-01-01 08:00:01')->comment('报名开始时间')->nullable();
            $table->timestamp('etime')->index()->default('1970-01-01 08:00:01')->comment('报名结束时间')->nullable();
            $table->tinyInteger('checked')->index()->comment('审核')->nullable();
            $table->string('address')->comment('地点')->nullable();
            $table->timestamp('activitytime')->default('1970-01-01 08:00:01')->comment('活动开始时间')->nullable();
            $table->string('editor')->default('五里桥街道')->comment('编辑')->nullable();
            $table->text('newstext')->comment('活动正文')->nullable();
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
        Schema::dropIfExists('activity');
    }
}
