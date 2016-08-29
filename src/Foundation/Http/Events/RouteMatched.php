<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 00:16
 */
namespace Notadd\Foundation\Http\Events;
use Notadd\Routing\RouteCollector;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Class RouteMatched
 * @package Notadd\Foundation\Http\Events\
 */
class RouteMatched {
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;
    /**
     * @var \Notadd\Routing\RouteCollector
     */
    protected $route;
    /**
     * RouteMatched constructor.
     * @param \Notadd\Routing\RouteCollector $route
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(RouteCollector $route, Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
        $this->route = $route;
    }
}