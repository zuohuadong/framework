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
 * Class CreateFlashesTable
 */
class CreateFlashesTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        $this->schema->create('flashes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('alias');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        $this->schema->drop('flashes');
    }
}