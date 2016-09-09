<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 20:03
 */
namespace Notadd\Foundation\Api\Middlewares;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\ErrorMiddlewareInterface;
/**
 * Class ErrorHandler
 * @package Notadd\Foundation\Api\Middlewares
 */
class ErrorHandler implements ErrorMiddlewareInterface {
    /**
     * @var \Notadd\Foundation\Api\Middlewares\ErrorHandler
     */
    protected $errorHandler;
    /**
     * ErrorHandler constructor.
     * @param \Notadd\Foundation\Api\Middlewares\ErrorHandler $errorHandler
     */
    public function __construct(ErrorHandler $errorHandler) {
        $this->errorHandler = $errorHandler;
    }
    /**
     * @param mixed $error
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable|null $out
     * @return mixed
     */
    public function __invoke($error, Request $request, Response $response, callable $out = null) {
        return $this->errorHandler->handle($e);
    }
}