<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-20 18:30
 */
namespace Notadd\Foundation\Http\Middlewares;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Notadd\Foundation\Application;
use Notadd\Foundation\Routing\RouteCollector;
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
     * @var \Notadd\Foundation\Routing\RouteCollector
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
        return null;
    }
}