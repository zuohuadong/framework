<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 18:26
 */
namespace Notadd\Admin\Listeners;
use Notadd\Admin\Controllers\AdminController;
use Notadd\Foundation\Abstracts\AbstractEventSubscriber;
use Notadd\Foundation\Routing\Events\RouteRegister as RouteRegisterEvent;
/**
 * Class RouteRegister
 * @package Notadd\Admin\Listeners
 */
class RouteRegister extends AbstractEventSubscriber {
    /**
     * @return mixed
     */
    public function getEvent() {
        return RouteRegisterEvent::class;
    }
    /**
     * @param \Notadd\Foundation\Routing\Events\RouteRegister $router
     */
    public function handle(RouteRegisterEvent $router) {
        $router->resource('/admin', AdminController::class);
    }
}