<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-20 00:51
 */
namespace Notadd\Foundation\Http;
use Notadd\Foundation\Abstracts\AbstractServer;
use Notadd\Foundation\Application;
use Notadd\Foundation\Http\Events\MiddlewareConfigurer;
use Notadd\Foundation\Http\Middlewares\AuthenticateWithSession;
use Notadd\Foundation\Http\Middlewares\ErrorHandler;
use Notadd\Foundation\Http\Middlewares\JsonBodyParser;
use Notadd\Foundation\Http\Middlewares\RememberFromCookie;
use Notadd\Foundation\Http\Middlewares\RouteDispatcher;
use Notadd\Foundation\Http\Middlewares\SessionStarter;
use Notadd\Install\InstallServiceProvider;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Server as ZendServer;
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
        $path = parse_url($this->getUrl(), PHP_URL_PATH);
        if(!$app->isInstalled()) {
            $app->register(InstallServiceProvider::class);
            $pipe->pipe($path, $app->make(RouteDispatcher::class));
            $pipe->pipe($path, new ErrorHandler($errorDir, true, $app->make('log')));
        } elseif($app->isInstalled()) {
            $pipe->pipe($path, $app->make(JsonBodyParser::class));
            $pipe->pipe($path, $app->make(SessionStarter::class));
            $pipe->pipe($path, $app->make(RememberFromCookie::class));
            $pipe->pipe($path, $app->make(AuthenticateWithSession::class));
            $app->make('events')->fire(new MiddlewareConfigurer($pipe, $path, $this));
            $pipe->pipe($path, $app->make(RouteDispatcher::class));
            $pipe->pipe($path, new ErrorHandler($errorDir, true, $app->make('log')));
        } else {
            $pipe->pipe($path, function () use ($errorDir) {
                return new HtmlResponse(file_get_contents($errorDir.'/503.html', 503));
            });
        }
        return $pipe;
    }
    /**
     * @param $path
     * @return mixed|string
     */
    protected function getUrl($path = null) {
        $config = [
            'url' => 'http://notadd.io',
            'paths' => [
                'api' => 'api',
                'admin' => 'admin',
            ]
        ];
        $url = array_get($config, 'url', $_SERVER['REQUEST_URI']);
        if(is_array($url)) {
            if(isset($url[$path])) {
                return $url[$path];
            }
            $url = $url['base'];
        }
        if($path) {
            $url .= '/' . array_get($config, "paths.$path", $path);
        }
        return $url;
    }
    /**
     * @return void
     */
    public function listen() {
        $app = $this->getApp();
        $server = ZendServer::createServer($this->getMiddleware($app), $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        $server->listen();
    }
}