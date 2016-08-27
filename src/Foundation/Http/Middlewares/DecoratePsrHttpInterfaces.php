<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 10:57
 */
namespace Notadd\Foundation\Http\Middlewares;
use Notadd\Foundation\Application;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Class DecoratePsrHttpInterfaces
 * @package Notadd\Foundation\Http\Middlewares
 */
class DecoratePsrHttpInterfaces {
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $application;
    /**
     * DecoratePsrHttpInterfaces constructor.
     * @param \Notadd\Foundation\Application $application
     */
    public function __construct(Application $application) {
        $this->application = $application;
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable|null $out
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $out = null) {
        $this->application->instance(Request::class, $request);
        $this->application->instance(Response::class, $response);
        return $out ? $out($request, $response) : $response;
    }
}