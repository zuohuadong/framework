<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-20 17:03
 */
namespace Notadd\Foundation\Routing;
use Illuminate\Support\ServiceProvider;
/**
 * Class RouterServiceProvider
 * @package Notadd\Foundation\Routing
 */
class RouterServiceProvider extends ServiceProvider {
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('url', function() {
            return new UrlGenerator($this->app);
        });
        $this->app->singleton('router', function() {
            return new Router($this->app, $this->app['events']);
        });
    }
}