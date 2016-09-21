<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-20 18:30
 */
namespace Notadd\Foundation\Http\Middlewares;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Illuminate\View\View;
use Notadd\Foundation\Routing\Events\RouteRegister;
use Notadd\Foundation\Routing\Responses\RedirectResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response as DiactorosResponse;
use Zend\Stratigility\MiddlewareInterface;
/**
 * Class DispatchRoute
 * @package Notadd\Foundation\Http\Middlewares
 */
class RouteDispatcher implements MiddlewareInterface {
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * @var \Notadd\Foundation\Routing\Router
     */
    protected $router;
    /**
     * RouteDispatcher constructor.
     * @param \Illuminate\Container\Container $container
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function __construct(Container $container, EventsDispatcher $events) {
        $this->container = $container;
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
        $this->router = $this->container->make('router');
        $this->events->fire(new RouteRegister($this->container, $this->router));
        $this->container->alias('redirect', RedirectResponse::class);
        $this->container->instance(RedirectResponse::class, function() use($request) {
            $redirector = new RedirectResponse($request->getUri()->getHost());
            return $redirector;
        });
        $this->container->instance(Request::class, $request);
        $this->container->instance(Response::class, $response);
        $return = $this->router->dispatch($request);
        if($return instanceof View) {
            $response = new DiactorosResponse();
            $response->getBody()->write($return);
            return $response;
        }
        return $return;
    }
}