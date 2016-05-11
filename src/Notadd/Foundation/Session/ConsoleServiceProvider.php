<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 19:54
 */
namespace Notadd\Foundation\Session;
use Illuminate\Session\Console\SessionTableCommand;
use Illuminate\Support\ServiceProvider;
/**
 * Class ConsoleServiceProvider
 * @package Notadd\Foundation\Session
 */
class ConsoleServiceProvider extends ServiceProvider {
    /**
     * @var bool
     */
    protected $defer = true;
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('command.session.database', function ($app) {
            return new SessionTableCommand($app['files'], $app['composer']);
        });
        $this->commands('command.session.database');
    }
    /**
     * @return array
     */
    public function provides() {
        return ['command.session.database'];
    }
}