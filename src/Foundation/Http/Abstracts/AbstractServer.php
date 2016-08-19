<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-20 00:46
 */
namespace Notadd\Foundation\Http\Abstracts;
use Notadd\Foundation\Abstracts\AbstractServer as BaseAbstractServer;
use Notadd\Foundation\Application;
use Zend\Diactoros\Server;
/**
 * Class AbstractServer
 * @package Notadd\Foundation\Http\Abstracts
 */
abstract class AbstractServer extends BaseAbstractServer {
    /**
     * @param \Notadd\Foundation\Application $app
     * @return \Zend\Stratigility\MiddlewareInterface
     */
    abstract protected function getMiddleware(Application $app);
    /**
     * @return void
     */
    public function listen() {
        $app = $this->getApp();
        $server = Server::createServer($this->getMiddleware($app), $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        $server->listen();
    }
}