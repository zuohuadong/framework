<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-29 17:42
 */
namespace Notadd\Foundation\Http\Middlewares;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;
/**
 * Class JsonBodyParser
 * @package Notadd\Foundation\Http\Middlewares
 */
class JsonBodyParser implements MiddlewareInterface {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable|null $out
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $out = null) {
        if(Str::contains($request->getHeaderLine('content-type'), 'json')) {
            $input = json_decode($request->getBody(), true);
            $request = $request->withParsedBody($input ?: []);
        }
        return $out ? $out($request, $response) : $response;
    }
}