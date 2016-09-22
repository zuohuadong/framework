<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 15:46
 */
namespace Notadd\Foundation\Http\Events;
use Notadd\Foundation\Abstracts\AbstractServer;
use Zend\Stratigility\MiddlewarePipe;
/**
 * Class MiddlewareConfigurer
 * @package Notadd\Foundation\Http\Events
 */
class PipelineInjection {
    /**
     * @var string
     */
    protected $path;
    /**
     * @var \Zend\Stratigility\MiddlewarePipe
     */
    protected $pipe;
    /**
     * @var \Notadd\Foundation\Abstracts\AbstractServer
     */
    protected $server;
    /**
     * MiddlewareConfigurer constructor.
     * @param \Zend\Stratigility\MiddlewarePipe $pipe
     * @param string $path
     * @param \Notadd\Foundation\Abstracts\AbstractServer $server
     */
    public function __construct(MiddlewarePipe $pipe, $path, AbstractServer $server) {
        $this->pipe = $pipe;
        $this->path = $path;
        $this->server = $server;
    }
    /**
     * @param callable $middleware
     */
    public function pipe(callable $middleware) {
        $this->pipe->pipe($this->path, $middleware);
    }
}