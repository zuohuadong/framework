<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-20 18:30
 */
namespace Notadd\Foundation\Http\Middlewares;
use FastRoute\Dispatcher;
use Notadd\Foundation\Http\Exceptions\MethodNotAllowedException;
use Notadd\Foundation\Http\Exceptions\RouteNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Class DispatchRoute
 * @package Notadd\Foundation\Http\Middlewares
 */
class RouteDispatcher {
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
        if(!isset($this->dispatcher)) {
            $this->dispatcher = new Dispatcher\GroupCountBased([]);
        }
        return $this->dispatcher;
    }
}