<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxuserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wxuser', function (Blueprint $table) {
            $table->increments('id');
            $table->string('openid')->index()->comment('用户的唯一标识');
            $table->string('nickname')->comment('用户昵称');
            $table->tinyInteger('sex')->comment('用户的性别，值为1时是男性，值为2时是女性，值为0时是未知')->nullable();
            $table->string('language')->comment('语言')->nullable();
            $table->string('province')->comment('用户个人资料填写的省份')->nullable();
            $table->string('city')->comment('普通用户个人资料填写的城市')->nullable();
            $table->string('country')->comment('国家，如中国为CN')->nullable();
            $table->string('headimgurl')->comment('用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。')->nullable();
            $table->string('privilege')->comment('用户特权信息，json 数组，如微信沃卡用户为（chinaunicom）')->nullable();
            $table->string('unionid')->comment('只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。')->nullable();
            $table->string('truename')->comment('用户真实姓名')->nullable();
            $table->string('mobile')->index()->comment('手机')->nullable();
            $table->string('address')->comment('联系地址')->nullable();
            $table->tinyInteger('volunteer')->comment('是否为志愿者')->nullable();
            $table->tinyInteger('partymember')->comment('是否为党员')->nullable();
            $table->integer('points')->comment('我的积分')->nullable();
            $table->integer('volunteer_points')->comment('志愿者积分')->nullable();
            $table->integer('partymember_points')->comment('党性积分')->nullable();
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
        Schema::dropIfExists('wxuser');
    }
}
