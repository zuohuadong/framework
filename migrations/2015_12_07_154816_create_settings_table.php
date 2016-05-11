<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-01 13:42
 */
use Illuminate\Database\Schema\Blueprint;
use Notadd\Foundation\Database\Migrations\Migration;
class CreateSettingsTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        $this->schema->create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        $this->schema->drop('settings');
    }
}