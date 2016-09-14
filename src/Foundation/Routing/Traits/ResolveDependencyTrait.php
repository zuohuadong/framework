<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-14 14:32
 */
namespace Notadd\Foundation\Routing\Traits;
use Illuminate\Support\Arr;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;
/**
 * Class ResolveDependencyTrait
 * @package Notadd\Foundation\Routing\Traits
 */
trait ResolveDependencyTrait {
    /**
     * @param $class
     * @param array $parameters
     * @return bool
     */
    protected function alreadyInParameters($class, array $parameters) {
        return !is_null(Arr::first($parameters, function ($value) use ($class) {
            return $value instanceof $class;
        }));
    }
    /**
     * @param array $parameters
     * @param $instance
     * @param string $method
     * @return array
     */
    protected function resolveClassMethodDependencies(array $parameters, $instance, $method) {
        if(!method_exists($instance, $method)) {
            return [];
        }
        return $this->resolveMethodDependencies($parameters, new ReflectionMethod($instance, $method));
    }
    /**
     * @param array $parameters
     * @param \ReflectionFunctionAbstract $reflector
     * @return array
     */
    public function resolveMethodDependencies(array $parameters, ReflectionFunctionAbstract $reflector) {
        foreach($reflector->getParameters() as $key => $parameter) {
            $instance = $this->transformDependency($parameter, $parameters);
            if(!is_null($instance)) {
                $this->spliceIntoParameters($parameters, $key, $instance);
            }
        }
        return $parameters;
    }
    /**
     * @param array $parameters
     * @param $key
     * @param $instance
     */
    protected function spliceIntoParameters(array &$parameters, $key, $instance) {
        array_splice($parameters, $key, 0, [$instance]);
    }
    /**
     * @param \ReflectionParameter $parameter
     * @param $parameters
     * @return mixed
     */
    protected function transformDependency(ReflectionParameter $parameter, $parameters) {
        $class = $parameter->getClass();
        if($class && !$this->alreadyInParameters($class->name, $parameters)) {
            return $this->container->make($class->name);
        }
        return null;
    }
}