<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-20 18:30
 */
namespace Notadd\Foundation\Http\Middlewares;
use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Notadd\Foundation\Application;
use Notadd\Foundation\Http\Events\RouteMatched;
use Notadd\Foundation\Http\Events\RouteRegister;
use Notadd\Foundation\Http\Exceptions\MethodNotAllowedException;
use Notadd\Foundation\Http\Exceptions\RouteNotFoundException;
use Notadd\Foundation\Http\Routing\RouteCollector;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;
/**
 * Class DispatchRoute
 * @package Notadd\Foundation\Http\Middlewares
 */
class RouteDispatcher implements MiddlewareInterface {
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $application;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * @var \Notadd\Foundation\Http\Routing\RouteCollector
     */
    protected $router;
    /**
     * RouteDispatcher constructor.
     * @param \Notadd\Foundation\Application $application
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function __construct(Application $application, EventsDispatcher $events) {
        $this->application = $application;
        $this->application->singleton('router', RouteCollector::class);
        $this->events = $events;
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable|null $out
     * @return mixed
     * @throws \Notadd\Foundation\Http\Exceptions\MethodNotAllowedException
     * @throws \Notadd\Foundation\Http\Exceptions\RouteNotFoundException
     */
    public function __invoke(Request $request, Response $response, callable $out = null) {
        $method = $request->getMethod();
        $uri = $request->getUri()->getPath() ?: '/';
        $routeInfo = $this->getDispatcher()->dispatch($method, $uri);
        switch($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new RouteNotFoundException;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException;
            case Dispatcher::FOUND:
                $this->events->fire(new RouteMatched($this->router, $request, $response));
                $handler = $routeInfo[1];
                $parameters = $routeInfo[2];
                return $handler($request, $parameters);
        }
        return null;
    }
    /**
     * @return \FastRoute\Dispatcher\GroupCountBased
     */
    protected function getDispatcher() {
        $this->router = $this->application->make('router');
        $this->events->fire(new RouteRegister($this->application, $this->router));
        if(!isset($this->dispatcher)) {
            $this->dispatcher = new GroupCountBased($this->router->getRouteData());
        }
        return $this->dispatcher;
    }
}