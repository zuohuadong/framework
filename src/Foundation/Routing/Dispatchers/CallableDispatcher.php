<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-21 18:13
 */
namespace Notadd\Foundation\Routing\Dispatchers;
use Illuminate\Container\Container;
use Notadd\Foundation\Routing\Traits\ResolveDependencyTrait;
use ReflectionFunction;
/**
 * Class CallableDispatcher
 * @package Notadd\Foundation\Routing\Dispatchers
 */
class CallableDispatcher {
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
     * @param callable $closure
     * @param array $parameters
     * @return null
     */
    public function dispatch(callable $closure, array $parameters) {
        $parameters = $this->resolveMethodDependencies($parameters, new ReflectionFunction($closure));
        return $closure(...array_values($parameters));
    }
}