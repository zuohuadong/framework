<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-21 14:56
 */
namespace Notadd\Foundation\Http\Routing;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteParser\Std;
/**
 * Class RouteCollector
 * @package Notadd\Foundation\Http
 */
class RouteCollector {
    /**
     * @var \FastRoute\DataGenerator\GroupCountBased
     */
    protected $dataGenerator;
    /**
     * @var array
     */
    protected $reverse = [];
    /**
     * @var \FastRoute\RouteParser\Std
     */
    protected $routeParser;
    /**
     * RouteCollector constructor.
     * @param \FastRoute\DataGenerator\GroupCountBased $dataGenerator
     * @param \FastRoute\RouteParser\Std $routeParser
     */
    public function __construct(GroupCountBased $dataGenerator, Std $routeParser) {
        $this->dataGenerator = $dataGenerator;
        $this->routeParser = $routeParser;
    }
    /**
     * @param $path
     * @param $name
     * @param $handler
     * @return \Notadd\Foundation\Http\Routing\RouteCollector
     */
    public function get($path, $name, $handler) {
        return $this->addRoute('GET', $path, $name, $handler);
    }
    /**
     * @param $path
     * @param $name
     * @param $handler
     * @return \Notadd\Foundation\Http\Routing\RouteCollector
     */
    public function post($path, $name, $handler) {
        return $this->addRoute('POST', $path, $name, $handler);
    }
    /**
     * @param $path
     * @param $name
     * @param $handler
     * @return \Notadd\Foundation\Http\Routing\RouteCollector
     */
    public function put($path, $name, $handler) {
        return $this->addRoute('PUT', $path, $name, $handler);
    }
    /**
     * @param $path
     * @param $name
     * @param $handler
     * @return \Notadd\Foundation\Http\Routing\RouteCollector
     */
    public function patch($path, $name, $handler) {
        return $this->addRoute('PATCH', $path, $name, $handler);
    }
    /**
     * @param $path
     * @param $name
     * @param $handler
     * @return \Notadd\Foundation\Http\Routing\RouteCollector
     */
    public function delete($path, $name, $handler) {
        return $this->addRoute('DELETE', $path, $name, $handler);
    }
    /**
     * @param $method
     * @param $path
     * @param $name
     * @param $handler
     * @return \Notadd\Foundation\Http\Routing\RouteCollector
     */
    public function addRoute($method, $path, $name, $handler) {
        $routeDatas = $this->routeParser->parse($path);
        foreach($routeDatas as $routeData) {
            $this->dataGenerator->addRoute($method, $routeData, $handler);
        }
        $this->reverse[$name] = $routeDatas;
        return $this;
    }
    /**
     * @return array
     */
    public function getRouteData() {
        return $this->dataGenerator->getData();
    }
    /**
     * @param $part
     * @param $key
     * @param array $parameters
     */
    protected function fixPathPart(&$part, $key, array $parameters) {
        if(is_array($part) && array_key_exists($part[0], $parameters)) {
            $part = $parameters[$part[0]];
        }
    }
    /**
     * @param $name
     * @param array $parameters
     * @return string
     */
    public function getPath($name, array $parameters = []) {
        if(isset($this->reverse[$name])) {
            $parts = $this->reverse[$name][0];
            array_walk($parts, [
                $this,
                'fixPathPart'
            ], $parameters);
            return '/' . ltrim(implode('', $parts), '/');
        }
        throw new \RuntimeException("Route $name not found");
    }
}