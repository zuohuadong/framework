<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-31 18:03
 */
namespace Notadd\Foundation\Database;
use Illuminate\Database\DatabaseServiceProvider as IlluminateDatabaseServiceProvider;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Notadd\Foundation\Database\Listeners\CommandRegister;
use Notadd\Foundation\Database\Migrations\MigrationCreator;
use Notadd\Foundation\Database\Migrations\Migrator;
/**
 * Class DatabaseServiceProvider
 * @package Notadd\Foundation\Database
 */
class DatabaseServiceProvider extends IlluminateDatabaseServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        parent::boot();
        $this->app->make('events')->subscribe(CommandRegister::class);
    }
    /**
     * @return void
     */
    public function register() {
        parent::register();
        $this->app->singleton('migration.repository', function ($app) {
            $table = $app['config']['database.migrations'];
            return new DatabaseMigrationRepository($app['db'], $table);
        });
        $this->app->singleton('migrator', function ($app) {
            $repository = $app['migration.repository'];
            return new Migrator($app, $repository, $app['db'], $app['files']);
        });
        $this->app->singleton('migration.creator', function ($app) {
            return new MigrationCreator($app['files']);
        });
    }
}