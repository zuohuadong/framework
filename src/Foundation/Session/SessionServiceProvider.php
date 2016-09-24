<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-24 14:46
 */
namespace Notadd\Foundation\Session;
use Illuminate\Session\SessionServiceProvider as IlluminateSessionServiceProvider;
/**
 * Class SessionServiceProvider
 * @package Notadd\Foundation\Session
 */
class SessionServiceProvider extends IlluminateSessionServiceProvider {
    /**
     * @return void
     */
    public function register() {
        $this->registerSessionManager();
        $this->registerSessionDriver();
    }
    /**
     * @return void
     */
    protected function registerSessionManager() {
        $this->app->singleton('session', function ($app) {
            return new SessionManager($app);
        });
    }
}