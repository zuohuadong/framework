<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 18:04
 */
namespace Notadd\Upgrade\Listeners;
use Notadd\Foundation\Abstracts\AbstractEventSubscriber;
use Notadd\Foundation\Routing\Events\RouteRegister as RouteRegisterEvent;
use Notadd\Upgrade\Controllers\UpgradeController;
/**
 * Class RouteRegister
 * @package Notadd\Upgrade\Listeners
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
        $router->get('upgrade', 'upgrade', UpgradeController::class);
    }
}