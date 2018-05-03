<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePpointslogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ppointslog', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid')->index()->comment('用户id');
            $table->string('openid')->index()->comment('用户openid');
            $table->integer('delta')->comment('增量分值');
            $table->string('desc')->comment('说明')->nullable();
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
        Schema::dropIfExists('ppointslog');
    }
}
