<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-20 00:51
 */
namespace Notadd\Foundation\Http;
use Notadd\Foundation\Application;
use Notadd\Foundation\Http\Abstracts\AbstractServer;
use Notadd\Foundation\Http\Middlewares\RouteDispatcher;
use Zend\Stratigility\MiddlewarePipe;
/**
 * Class Server
 * @package Notadd\Foundation\Http
 */
class Server extends AbstractServer {
    /**
     * @param \Notadd\Foundation\Application $app
     * @return \Zend\Stratigility\MiddlewareInterface
     */
    protected function getMiddleware(Application $app) {
        $pipe = new MiddlewarePipe;
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pipe->pipe($path, $app->make(RouteDispatcher::class));
        return $pipe;
    }
}