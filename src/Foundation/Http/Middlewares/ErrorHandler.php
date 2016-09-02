<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 11:34
 */
namespace Notadd\Foundation\Http\Middlewares;
use Franzl\Middleware\Whoops\ErrorMiddleware as WhoopsMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Stratigility\ErrorMiddlewareInterface;
/**
 * Class ErrorHandler
 * @package Notadd\Foundation\Http\Middlewares
 */
class ErrorHandler implements ErrorMiddlewareInterface {
    /**
     * @var string
     */
    protected $templateDir;
    /**
     * @var bool
     */
    protected $debug;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * ErrorHandler constructor.
     * @param $templateDir
     * @param bool $debug
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct($templateDir, $debug = false, LoggerInterface $logger = null) {
        $this->templateDir = $templateDir;
        $this->debug = $debug;
        $this->logger = $logger;
    }
    /**
     * @param mixed $error
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable|null $out
     * @return \Zend\Diactoros\Response\HtmlResponse
     */
    public function __invoke($error, Request $request, Response $response, callable $out = null) {
        $status = 500;
        $errorCode = $error->getCode();
        if(is_int($errorCode) && $errorCode >= 400 && $errorCode < 600) {
            $status = $errorCode;
        }
        if($this->debug && !in_array($errorCode, [
                403,
                404
            ])
        ) {
            if($this->logger instanceof LoggerInterface) {
                $this->logger->error($error);
            }
            $whoops = new WhoopsMiddleware;
            return $whoops($error, $request, $response, $out);
        }
        $errorPage = $this->getErrorPage($status);
        return new HtmlResponse($errorPage, $status);
    }
    /**
     * @param string $status
     * @return string
     */
    protected function getErrorPage($status) {
        if(!file_exists($errorPage = $this->templateDir . "/$status.html")) {
            $errorPage = $this->templateDir . '/500.html';
        }
        return file_get_contents($errorPage);
    }
}