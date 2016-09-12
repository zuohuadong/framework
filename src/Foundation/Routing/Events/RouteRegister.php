<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-26 13:34
 */
namespace Notadd\Foundation\Routing\Events;
use Notadd\Foundation\Application;
use Notadd\Foundation\Routing\Registrars\ResourceRegistrar;
use Notadd\Foundation\Routing\RouteCollector;
use Notadd\Foundation\Routing\Traits\GetHandlerGeneratorTrait;
use Notadd\Foundation\Routing\Traits\ResolveClassMethodTrait;
/**
 * Class RouteRegister
 * @package Notadd\Routing\Events
 */
class RouteRegister {
    use GetHandlerGeneratorTrait, ResolveClassMethodTrait;
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $application;
    /**
     * @var \Notadd\Foundation\Routing\RouteCollector
     */
    protected $router;
    /**
     * RouteRegister constructor.
     * @param \Notadd\Foundation\Application $application
     * @param \Notadd\Foundation\Routing\RouteCollector $router
     */
    public function __construct(Application $application, RouteCollector $router) {
        $this->application = $application;
        $this->router = $router;
    }
    /**
     * @param $path
     * @param $handler
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function delete($path, $handler) {
        list($class, $method) = $this->resolveClassMethod($handler);
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('DELETE', $path, $toController($class, $method));
    }
    /**
     * @param $path
     * @param $handler
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function get($path, $handler) {
        list($class, $method) = $this->resolveClassMethod($handler);
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('GET', $path, $toController($class, $method));
    }
    /**
     * @param $path
     * @param $handler
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function post($path, $handler) {
        list($class, $method) = $this->resolveClassMethod($handler);
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('POST', $path, $toController($class, $method));
    }
    /**
     * @param $path
     * @param $handler
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function put($path, $handler) {
        list($class, $method) = $this->resolveClassMethod($handler);
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('PUT', $path, $toController($class, $method));
    }
    /**
     * @param $path
     * @param $handler
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function patch($path, $handler) {
        list($class, $method) = $this->resolveClassMethod($handler);
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('PATCH', $path, $toController($class, $method));
    }
    /**
     * @param string $path
     * @param string $controller
     * @param array $options
     */
    public function resource($path, $controller, array $options = []) {
        if($this->application->bound(ResourceRegistrar::class)) {
            $registrar = $this->application->make(ResourceRegistrar::class);
        } else {
            $registrar = new ResourceRegistrar($this->application, $this->router);
        }
        $registrar->register($path, $controller, $options);
    }
}