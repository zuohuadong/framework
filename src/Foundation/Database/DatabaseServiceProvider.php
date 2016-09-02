<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-31 18:03
 */
namespace Notadd\Foundation\Database;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\DatabaseServiceProvider as IlluminateDatabaseServiceProvider;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\Migrator;
use Notadd\Foundation\Database\Listeners\CommandRegister;
/**
 * Class DatabaseServiceProvider
 * @package Notadd\Foundation\Database
 */
class DatabaseServiceProvider extends IlluminateDatabaseServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->app->make('events')->subscribe(CommandRegister::class);
    }
    /**
     * @return void
     */
    public function register() {
        parent::register();
        $this->app->alias('db.connection', ConnectionInterface::class);
        $this->app->singleton('migration.repository', function ($app) {
            $table = $app['config']['database.migrations'];
            return new DatabaseMigrationRepository($app['db'], $table);
        });
        $this->app->singleton('migrator', function ($app) {
            $repository = $app['migration.repository'];
            return new Migrator($repository, $app['db'], $app['files']);
        });
        $this->app->singleton('migration.creator', function ($app) {
            return new MigrationCreator($app['files']);
        });
    }
}