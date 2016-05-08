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
 * Class ExtendUserInfoToUsersTable
 */
class ExtendUserInfoToUsersTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        $this->schema->table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('password');
            $table->string('qq_id')->nullable()->after('avatar');
            $table->string('wechat_id')->nullable()->after('avatar');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $this->schema->table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('qq_id');
            $table->dropColumn('wechat_id');
        });
    }
}