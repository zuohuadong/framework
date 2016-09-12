<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-12 17:40
 */
namespace Notadd\Foundation\Routing\Traits;
use InvalidArgumentException;
use Notadd\Foundation\Http\Contracts\ControllerContract;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
/**
 * Class GetHandlerGeneratorTrait
 * @package Notadd\Foundation\Routing\Traits
 */
trait GetHandlerGeneratorTrait {
    /**
     * @return \Closure
     */
    protected function getHandlerGenerator() {
        return function ($class, $function = 'handle') use ($container) {
            return function (ServerRequestInterface $request, $routeParams) use ($class, $function) {
                $controller = $this->application->make($class);
                if(!($controller instanceof ControllerContract)) {
                    throw new InvalidArgumentException('Route handler must be an instance of ' . ControllerContract::class);
                }
                $request = $request->withQueryParams(array_merge($request->getQueryParams(), $routeParams));
                $response = new Response;
                $response->getBody()->write($this->application->call([$controller, $function]));
                return $response;
            };
        };
    }
}