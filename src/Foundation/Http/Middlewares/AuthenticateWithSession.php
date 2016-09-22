<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-31 14:35
 */
namespace Notadd\Foundation\Http\Middlewares;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Class AuthenticateWithSession
 * @package Notadd\Foundation\Http\Middlewares
 */
class AuthenticateWithSession {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param callable $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(Request $request, callable $next = null) {
        return $next($request);
    }
}