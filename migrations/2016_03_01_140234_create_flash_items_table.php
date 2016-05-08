<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-01 13:42
 */
use Illuminate\Database\Schema\Blueprint;
use Notadd\Foundation\Database\Migrations\Migration;
/**
 * Class CreateFlashItemsTable
 */
class CreateFlashItemsTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        $this->schema->create('flash_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->string('title');
            $table->string('link')->nullable();
            $table->enum('link_target', ['_blank', '_self', '_parent', '_top'])->default('_blank');
            $table->string('alt_info')->nullable();
            $table->string('thumb_image')->nullable();
            $table->string('full_image')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        $this->schema->drop('flash_items');
    }
}