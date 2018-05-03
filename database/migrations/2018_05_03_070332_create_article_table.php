<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('文章标题');
            $table->tinyInteger('checked')->comment('审核状态')->nullable();
            $table->text('url')->comment('文章链接');
            $table->timestamp('newstime')->default('1970-01-01 08:00:01')->comment('新闻时间')->nullable();
            $table->string('top')->comment('推荐')->nullable();
            $table->text('intro')->comment('简介')->nullable();
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
        Schema::dropIfExists('article');
    }
}
