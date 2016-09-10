<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-10 11:32
 */
namespace Notadd\Foundation\Admin;
use Notadd\Foundation\Application;
use Notadd\Foundation\Http\Abstracts\AbstractServer;
use Notadd\Foundation\Http\Events\MiddlewareConfigurer;
use Notadd\Foundation\Http\Middlewares\AuthenticateWithSession;
use Notadd\Foundation\Http\Middlewares\DecoratePsrHttpInterfaces;
use Notadd\Foundation\Http\Middlewares\ErrorHandler;
use Notadd\Foundation\Http\Middlewares\JsonBodyParser;
use Notadd\Foundation\Http\Middlewares\RememberFromCookie;
use Notadd\Foundation\Http\Middlewares\RouteDispatcher;
use Notadd\Foundation\Http\Middlewares\SessionStarter;
use Notadd\Upgrade\UpgradeServiceProvider;
use Zend\Stratigility\MiddlewarePipe;
/**
 * Class Server
 * @package Notadd\Foundation\Admin
 */
class Server extends AbstractServer {
    /**
     * @param \Notadd\Foundation\Application $app
     * @return \Zend\Stratigility\MiddlewareInterface
     */
    protected function getMiddleware(Application $app) {
        $pipe = new MiddlewarePipe;
        if($app->isInstalled()) {
            $errorDir = realpath(__DIR__ . '/../../../errors');
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            if($app->isUpToDate()) {
                $pipe->pipe($path, $app->make(DecoratePsrHttpInterfaces::class));
                $pipe->pipe($path, $app->make(JsonBodyParser::class));
                $pipe->pipe($path, $app->make(SessionStarter::class));
                $pipe->pipe($path, $app->make(RememberFromCookie::class));
                $pipe->pipe($path, $app->make(AuthenticateWithSession::class));
                $app->make('events')->fire(new MiddlewareConfigurer($pipe, $path, $this));
                $pipe->pipe($path, $app->make(RouteDispatcher::class));
                $pipe->pipe($path, new ErrorHandler($errorDir, true, $app->make('log')));
            } else {
                $app->register(UpgradeServiceProvider::class);
                $pipe->pipe($path, $app->make(RouteDispatcher::class));
                $pipe->pipe($path, new ErrorHandler($errorDir, true, $app->make('log')));
            }
        }
        return $pipe;
    }
}