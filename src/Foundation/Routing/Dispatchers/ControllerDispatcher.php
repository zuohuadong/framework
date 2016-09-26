<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-21 18:14
 */
namespace Notadd\Foundation\Routing\Dispatchers;
use Illuminate\Container\Container;
use Notadd\Foundation\Routing\Contracts\Controller as ControllerContract;
use Notadd\Foundation\Http\Exceptions\MethodNotFoundException;
use Notadd\Foundation\Routing\Traits\ResolveDependencyTrait;
/**
 * Class ControllerDispatcher
 * @package Notadd\Foundation\Routing\Dispatchers
 */
class ControllerDispatcher {
    use ResolveDependencyTrait;
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;
    /**
     * @var \Notadd\Foundation\Routing\Router
     */
    protected $router;
    /**
     * ControllerDispatcher constructor.
     * @param \Illuminate\Container\Container $container
     * @param \Notadd\Foundation\Routing\Router $router
     */
    public function __construct(Container $container, \Notadd\Foundation\Routing\Router $router) {
        $this->container = $container;
        $this->router = $router;
    }
    /**
     * @param \Notadd\Foundation\Routing\Contracts\Controller $instance
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function callControllerCallable(ControllerContract $instance, $method, $parameters) {
        $parameters = $this->resolveClassMethodDependencies($parameters, $instance, $method);
        if(method_exists($instance, 'callAction')) {
            return $instance->callAction($method, $parameters);
        }
        return call_user_func_array([
            $instance,
            $method
        ], $parameters);
    }
    /**
     * @param \Notadd\Foundation\Routing\Contracts\Controller $instance
     * @param string $method
     * @param array $routeInfo
     * @return mixed
     */
    protected function callNotaddController(ControllerContract $instance, $method, $routeInfo) {
        $middleware = $instance->getMiddlewareForMethod($method);
        if(count($middleware) > 0) {
            return $this->callNotaddControllerWithMiddleware($instance, $method, $routeInfo, $middleware);
        } else {
            return $this->callControllerCallable($instance, $method, $routeInfo[2]);
        }
    }
    /**
     * @param mixed $instance
     * @param string $method
     * @param array $routeInfo
     * @param array $middleware
     * @return mixed
     */
    protected function callNotaddControllerWithMiddleware($instance, $method, $routeInfo, $middleware) {
        $middleware = $this->router->gatherMiddlewareClassNames($middleware);
        return $this->router->sendThroughPipeline($middleware, function () use ($instance, $method, $routeInfo) {
            return $this->callControllerCallable($instance, $method, $routeInfo[2]);
        });
    }
    /**
     * @param array $routeInfo
     * @return mixed
     * @throws \Notadd\Foundation\Http\Exceptions\MethodNotFoundException
     */
    public function dispatch(array $routeInfo) {
        list($class, $method) = explode('@', $routeInfo[1]['uses']);
        if(!method_exists($instance = $this->container->make($class), $method)) {
            throw new MethodNotFoundException("Controller method not found: {$class}@{$method}.");
        }
        if($instance instanceof ControllerContract) {
            return $this->callNotaddController($instance, $method, $routeInfo);
        } else {
            return $this->callControllerCallable($instance, $method, $routeInfo[2]);
        }
    }
}