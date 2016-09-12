<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-26 13:46
 */
namespace Notadd\Foundation\Http\Listeners;
use Notadd\Foundation\Abstracts\AbstractEventSubscriber;
use Notadd\Foundation\Http\Controllers\IndexController;
use Notadd\Foundation\Routing\Events\RouteRegister as RouteRegisterEvent;
/**
 * Class RouteRegister
 * @package Notadd\Foundation\Http\Listeners
 */
class RouteRegister extends AbstractEventSubscriber {
    /**
     * @return string
     */
    protected function getEvent() {
        return RouteRegisterEvent::class;
    }
    /**
     * @param \Notadd\Foundation\Routing\Events\RouteRegister $router
     */
    public function handle(RouteRegisterEvent $router) {
        $router->resource('/', IndexController::class);
    }
}