<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-26 13:34
 */
namespace Notadd\Foundation\Routing\Events;
use Illuminate\Container\Container;
use Notadd\Foundation\Routing\Router;
/**
 * Class RouteRegister
 * @package Notadd\Routing\Events
 */
class RouteRegister {
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;
    /**
     * @var \Notadd\Foundation\Routing\Router
     */
    protected $router;
    /**
     * RouteRegister constructor.
     * @param \Illuminate\Container\Container $container
     * @param \Notadd\Foundation\Routing\Router $router
     */
    public function __construct(Container $container, Router $router) {
        $this->container = $container;
        $this->router = $router;
    }
}