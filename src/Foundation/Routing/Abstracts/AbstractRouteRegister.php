<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-20 15:18
 */
namespace Notadd\Foundation\Routing\Abstracts;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Notadd\Foundation\Abstracts\AbstractEventSubscriber;
use Notadd\Foundation\Routing\Events\RouteRegister as RouteRegisterEvent;
use Notadd\Foundation\Routing\Router;
/**
 * Class AbstractRouteRegister
 * @package Notadd\Foundation\Routing\Abstracts
 */
abstract class AbstractRouteRegister extends AbstractEventSubscriber {
    /**
     * @var \Notadd\Foundation\Routing\Router
     */
    protected $router;
    /**
     * AbstractRouteRegister constructor.
     * @param \Illuminate\Container\Container $container
     * @param \Illuminate\Events\Dispatcher $events
     * @param \Notadd\Foundation\Routing\Router $router
     */
    public function __construct(Container $container, Dispatcher $events, Router $router) {
        parent::__construct($container, $events);
        $this->router = $router;
    }
    /**
     * @return string
     */
    protected function getEvent() {
        return RouteRegisterEvent::class;
    }
    /**
     * @return void
     */
    abstract public function handle();
}