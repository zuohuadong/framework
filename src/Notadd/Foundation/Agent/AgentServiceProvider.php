<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-28 13:25
 */
namespace Notadd\Foundation\Agent;
use Illuminate\Support\ServiceProvider;
/**
 * Class AgentServiceProvider
 * @package Notadd\Foundation\Agent
 */
class AgentServiceProvider extends ServiceProvider {
    /**
     * @var bool
     */
    protected $defer = false;
    /**
     * @return void
     */
    public function boot() {
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('agent', function($app) {
            return new Agent($this->app->make('request')->server->all());
        });
    }
}