<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 16:33
 */
namespace Notadd\Install\Listeners;
use Notadd\Foundation\Abstracts\AbstractEventSubscriber;
use Notadd\Foundation\Routing\Events\RouteRegister as RouteRegisterEvent;
use Notadd\Install\Controllers\IndexController;
use Notadd\Install\Controllers\InstallController;
/**
 * Class RouteRegister
 * @package Notadd\Install\Listeners
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
        $router->get('/', IndexController::class);
        $router->post('/', InstallController::class);
    }
}