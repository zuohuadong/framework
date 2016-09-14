<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-14 14:04
 */
namespace Notadd\Foundation\Routing\Dispatchers;
use Illuminate\Container\Container;
use InvalidArgumentException;
use Notadd\Foundation\Http\Contracts\ControllerContract;
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
     * CallableDispatcher constructor.
     * @param \Illuminate\Container\Container $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
    }
    /**
     * @param array $parameters
     * @param string $class
     * @param string $method
     * @return mixed
     */
    public function dispatch(array $parameters, $class, $method) {
        $controller = $this->container->make($class);
        if(!($controller instanceof ControllerContract)) {
            throw new InvalidArgumentException('Route handler must be an instance of ' . ControllerContract::class);
        }
        $parameters = $this->resolveClassMethodDependencies($parameters, $controller, $method);
        if(method_exists($controller, 'callAction')) {
            return $controller->callAction($method, $parameters);
        }
        return call_user_func_array([
            $controller,
            $method
        ], $parameters);
    }
}