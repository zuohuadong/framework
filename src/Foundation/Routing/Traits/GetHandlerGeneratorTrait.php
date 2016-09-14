<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-12 17:40
 */
namespace Notadd\Foundation\Routing\Traits;
use Illuminate\View\View;
use Notadd\Foundation\Routing\Dispatchers\CallableDispatcher;
use Notadd\Foundation\Routing\Dispatchers\ControllerDispatcher;
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
        return function ($class, $function = 'handle') {
            return function (ServerRequestInterface $request, $routeParams) use ($class, $function) {
                if($this->isControllerAction($class)) {
                    $request = $request->withQueryParams(array_merge($request->getQueryParams(), $routeParams));
                    $this->application->instance(ServerRequestInterface::class, $request);
                    $content = (new ControllerDispatcher($this->application))->dispatch($routeParams, $class, $function);
                } else {
                    $content = (new CallableDispatcher($this->application))->dispatch($routeParams, $class);
                }
                if($content instanceof View) {
                    $response = new Response;
                    $response->getBody()->write($content);
                } else {
                    $response = $content;
                }
                return $response;
            };
        };
    }
    /**
     * @param $handler
     * @return bool
     */
    protected function isControllerAction($handler) {
        return is_string($handler);
    }
    /**
     * @param array $parameters
     * @return array
     */
    public function parametersWithoutNulls(array $parameters) {
        return array_filter($parameters, function ($p) {
            return !is_null($p);
        });
    }
}