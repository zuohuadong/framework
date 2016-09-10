<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 19:46
 */
namespace Notadd\Foundation\Api;
use Notadd\Foundation\Api\Middlewares\ErrorHandler;
use Notadd\Foundation\Api\Responses\JsonResponse;
use Notadd\Foundation\Application;
use Notadd\Foundation\Http\Abstracts\AbstractServer;
use Notadd\Foundation\Http\Events\MiddlewareConfigurer;
use Notadd\Foundation\Http\Middlewares\AuthenticateWithSession;
use Notadd\Foundation\Http\Middlewares\DecoratePsrHttpInterfaces;
use Notadd\Foundation\Http\Middlewares\JsonBodyParser;
use Notadd\Foundation\Http\Middlewares\RememberFromCookie;
use Notadd\Foundation\Http\Middlewares\RouteDispatcher;
use Notadd\Foundation\Http\Middlewares\SessionStarter;
use Tobscure\JsonApi\Document;
use Zend\Stratigility\MiddlewarePipe;
/**
 * Class Server
 * @package Notadd\Foundation\Api
 */
class Server extends AbstractServer {
    /**
     * @param \Notadd\Foundation\Application $app
     * @return \Zend\Stratigility\MiddlewarePipe
     */
    protected function getMiddleware(Application $app) {
        $pipe = new MiddlewarePipe;
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if($app->isInstalled() && $app->isUpToDate()) {
            $pipe->pipe($path, $app->make(DecoratePsrHttpInterfaces::class));
            $pipe->pipe($path, $app->make(JsonBodyParser::class));
            $pipe->pipe($path, $app->make(SessionStarter::class));
            $pipe->pipe($path, $app->make(RememberFromCookie::class));
            $pipe->pipe($path, $app->make(AuthenticateWithSession::class));
            $app->make('events')->fire(new MiddlewareConfigurer($pipe, $path, $this));
            $pipe->pipe($path, $app->make(RouteDispatcher::class));
            $pipe->pipe($path, $app->make(ErrorHandler::class));
        } else {
            $pipe->pipe($path, function () {
                $document = new Document;
                $document->setErrors([
                    [
                        'code' => 503,
                        'title' => 'Service Unavailable'
                    ]
                ]);
                return new JsonResponse($document, 503);
            });
        }
        return $pipe;
    }
}