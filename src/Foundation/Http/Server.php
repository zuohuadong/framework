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
use Notadd\Foundation\Http\Events\MiddlewareConfigurer;
use Notadd\Foundation\Http\Middlewares\DecoratePsrHttpInterfaces;
use Notadd\Foundation\Http\Middlewares\ErrorHandler;
use Notadd\Foundation\Http\Middlewares\RouteDispatcher;
use Zend\Diactoros\Response\HtmlResponse;
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
        $errorDir = realpath(__DIR__ . '/../../../errors');
        $pipe = new MiddlewarePipe;
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if($app->isInstalled()) {
            $app->register(HttpServiceProvider::class);
            $pipe->pipe($path, $app->make(DecoratePsrHttpInterfaces::class));
            $app->make('events')->fire(new MiddlewareConfigurer($pipe, $path, $this));
            $pipe->pipe($path, $app->make(RouteDispatcher::class));
            $pipe->pipe($path, new ErrorHandler($errorDir, true));
        } else {
            $pipe->pipe($path, function () use ($errorDir) {
                return new HtmlResponse(file_get_contents($errorDir.'/503.html', 503));
            });
        }
        return $pipe;
    }
}