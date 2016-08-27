<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-26 13:34
 */
namespace Notadd\Foundation\Http\Events;
use InvalidArgumentException;
use Notadd\Foundation\Application;
use Notadd\Foundation\Http\Contracts\ControllerContract;
use Notadd\Foundation\Http\Routing\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
/**
 * Class RouteRegister
 * @package Notadd\Foundation\Http\Events
 */
class RouteRegister {
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $application;
    /**
     * @var \Notadd\Foundation\Http\Routing\RouteCollector
     */
    protected $router;
    /**
     * RouteRegister constructor.
     * @param \Notadd\Foundation\Application $application
     * @param \Notadd\Foundation\Http\Routing\RouteCollector $router
     */
    public function __construct(Application $application, RouteCollector $router) {
        $this->application = $application;
        $this->router = $router;
    }
    /**
     * @param $path
     * @param $name
     * @param $class
     * @return \Notadd\Foundation\Http\Routing\RouteCollector
     */
    public function get($path, $name, $class) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('GET', $path, $name, $toController($class));
    }
    /**
     * @return \Closure
     */
    protected function getHandlerGenerator() {
        return function ($class, $function = 'handle') use ($container) {
            return function (ServerRequestInterface $request, $routeParams) use ($class, $function) {
                $controller = $this->application->make($class);
                if(!($controller instanceof ControllerContract)) {
                    throw new InvalidArgumentException('Route handler must be an instance of ' . ControllerContract::class);
                }
                $request = $request->withQueryParams(array_merge($request->getQueryParams(), $routeParams));
                $response = new Response;
                $response->getBody()->write($this->application->call([$controller, $function]));
                return $response;
            };
        };
    }
    /**
     * @param $path
     * @param $name
     * @param $class
     * @return \Notadd\Foundation\Http\Routing\RouteCollector
     */
    public function post($path, $name, $class) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('POST', $path, $name, $toController($class));
    }
    /**
     * @param $path
     * @param $name
     * @param $class
     * @return \Notadd\Foundation\Http\Routing\RouteCollector
     */
    public function put($path, $name, $class) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('PUT', $path, $name, $toController($class));
    }
    /**
     * @param $path
     * @param $name
     * @param $class
     * @return \Notadd\Foundation\Http\Routing\RouteCollector
     */
    public function patch($path, $name, $class) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('PATCH', $path, $name, $toController($class));
    }
    /**
     * @param $path
     * @param $name
     * @param $class
     * @return \Notadd\Foundation\Http\Routing\RouteCollector
     */
    public function delete($path, $name, $class) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('DELETE', $path, $name, $toController($class));
    }
}