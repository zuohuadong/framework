<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-28 23:16
 */
namespace Notadd\Foundation\Abstracts;
use Notadd\Foundation\Application;
use Notadd\Foundation\Routing\RouteCollector;
/**
 * Class AbstractUrlGenerator
 * @package Notadd\Foundation\Abstracts
 */
abstract class AbstractUrlGenerator {
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $application;
    /**
     * @var string
     */
    protected $path;
    /**
     * @var \Notadd\Foundation\Routing\RouteCollector
     */
    protected $route;
    /**
     * AbstractUrlGenerator constructor.
     * @param \Notadd\Foundation\Application $application
     * @param \Notadd\Foundation\Routing\RouteCollector $route
     */
    public function __construct(Application $application, RouteCollector $route) {
        $this->application = $application;
        $this->route = $route;
    }
    /**
     * @return string
     */
    public function toBase() {
        return $this->path;
    }
    /**
     * @param $path
     * @return string
     */
    public function toPath($path) {
        return $this->toBase() . '/' . $path;
    }
    /**
     * @param $name
     * @param array $parameters
     * @return string
     */
    public function toRoute($name, $parameters = []) {
        $path = $this->route->getPath($name, $parameters);
        $path = ltrim($path, '/');
        return $this->toBase() . '/' . $path;
    }
}