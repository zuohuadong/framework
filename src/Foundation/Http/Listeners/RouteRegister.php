<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-26 13:46
 */
namespace Notadd\Foundation\Http\Listeners;
use Illuminate\Events\Dispatcher;
use Notadd\Foundation\Application;
use Notadd\Foundation\Http\Controllers\IndexController;
use Notadd\Foundation\Http\Events\RouteRegister as RouteRegisterEvent;
/**
 * Class RouteRegister
 * @package Notadd\Foundation\Http\Listeners
 */
class RouteRegister {
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $application;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * RouteRegister constructor.
     * @param \Notadd\Foundation\Application $application
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function __construct(Application $application, Dispatcher $events) {
        $this->application = $application;
        $this->events = $events;
    }
    /**
     * @param \Notadd\Foundation\Http\Events\RouteRegister $router
     */
    public function handle(RouteRegisterEvent $router) {
        $router->get('/', 'default', IndexController::class);
    }
    /**
     * @return void
     */
    public function subscribe() {
        $this->events->listen(RouteRegisterEvent::class, [$this, 'handle']);
    }
}