<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-01 13:42
 */
use Illuminate\Database\Schema\Blueprint;
use Notadd\Foundation\Database\Migrations\Migration;
class CreateArticlesTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        $this->schema->create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('title');
            $table->string('author');
            $table->string('from_author');
            $table->string('from_url');
            $table->mediumText('content')->nullable();
            $table->string('keyword');
            $table->string('description');
            $table->string('thumb_image')->nullable();
            $table->integer('user_id');
            $table->integer('hits')->default(0);
            $table->boolean('is_sticky')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        $this->schema->drop('articles');
    }
}