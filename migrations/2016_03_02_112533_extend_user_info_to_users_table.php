<?php

use Notadd\Foundation\Database\Schema\Blueprint;
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