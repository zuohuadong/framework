<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-21 14:56
 */
namespace Notadd\Foundation\Routing;
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
     * @param $handler
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function get($path, $handler) {
        return $this->addRoute('GET', $path, $handler);
    }
    /**
     * @param $path
     * @param $handler
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function post($path, $handler) {
        return $this->addRoute('POST', $path, $handler);
    }
    /**
     * @param $path
     * @param $handler
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function put($path, $handler) {
        return $this->addRoute('PUT', $path, $handler);
    }
    /**
     * @param $path
     * @param $handler
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function patch($path, $handler) {
        return $this->addRoute('PATCH', $path, $handler);
    }
    /**
     * @param $path
     * @param $handler
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function delete($path, $handler) {
        return $this->addRoute('DELETE', $path, $handler);
    }
    /**
     * @param $method
     * @param $path
     * @param $handler
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addRoute($method, $path, $handler) {
        $path = '/' . trim($path, '/');
        $routeDatas = $this->routeParser->parse($path);
        foreach($routeDatas as $routeData) {
            $this->dataGenerator->addRoute($method, $routeData, $handler);
        }
        return $this;
    }
    /**
     * @return array
     */
    public function getRouteData() {
        return $this->dataGenerator->getData();
    }
}