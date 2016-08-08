<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-02-29 20:36
 */
namespace Notadd\Develop;
use Notadd\Develop\Controllers\Admin\MigrateController;
use Notadd\Develop\Controllers\Admin\MigrationController;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
/**
 * Class DevelopServiceProvider
 * @package Notadd\Develop
 */
class DevelopServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function() {
            $this->router->resource('migrate', MigrateController::class, ['only' => ['store']]);
            $this->router->resource('migration', MigrationController::class, ['only' => ['index', 'store']]);
        });
    }
    /**
     * @return void
     */
    public function register() {
    }
}