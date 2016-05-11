<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-01 13:42
 */
use Illuminate\Database\Schema\Blueprint;
use Notadd\Foundation\Database\Migrations\Migration;
class CreateMenusTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        $this->schema->create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->integer('group_id');
            $table->string('title');
            $table->string('tooltip')->nullable();
            $table->string('link');
            $table->enum('target', ['_blank', '_self', '_parent', '_top'])->default('_blank');
            $table->string('foreground_color')->nullable();
            $table->string('icon_image')->nullable();
            $table->tinyInteger('order_id')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        $this->schema->drop('menus');
    }
}