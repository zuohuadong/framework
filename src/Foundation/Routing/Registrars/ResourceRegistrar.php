<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-12 17:20
 */
namespace Notadd\Foundation\Routing\Registrars;
use Notadd\Foundation\Application;
use Notadd\Foundation\Routing\RouteCollector;
use Notadd\Foundation\Routing\Traits\GetHandlerGeneratorTrait;
/**
 * Class ResourceRegistrar
 * @package Notadd\Foundation\Routing\Registrars
 */
class ResourceRegistrar {
    use GetHandlerGeneratorTrait;
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $application;
    /**
     * @var array
     */
    protected $defaults = [
        'index',
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy'
    ];
    /**
     * @var \Notadd\Foundation\Routing\RouteCollector
     */
    protected $router;
    /**
     * ResourceRegistrar constructor.
     * @param \Notadd\Foundation\Application $application
     * @param \Notadd\Foundation\Routing\RouteCollector $router
     */
    public function __construct(Application $application, RouteCollector $router) {
        $this->application = $application;
        $this->router = $router;
    }
    /**
     * @param $defaults
     * @param $options
     * @return array
     */
    protected function getResourceMethods($defaults, $options) {
        if(isset($options['only'])) {
            return array_intersect($defaults, (array)$options['only']);
        } elseif(isset($options['except'])) {
            return array_diff($defaults, (array)$options['except']);
        }
        return $defaults;
    }
    /**
     * @param string $path
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceCreate($path, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('GET', $path . '/create', $toController($controller, 'create'));
    }
    /**
     * @param string $path
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceDestroy($path, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('DELETE', $path . '/{key}', $toController($controller, 'destroy'));
    }
    /**
     * @param string $path
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceEdit($path, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('GET', $path . '/{key}/edit', $toController($controller, 'edit'));
    }
    /**
     * @param string $path
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceIndex($path, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('GET', $path, $toController($controller, 'index'));
    }
    /**
     * @param string $path
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceShow($path, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('GET', $path . '/{key}', $toController($controller, 'show'));
    }
    /**
     * @param string $path
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceStore($path, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('POST', $path . '/{key}', $toController($controller, 'store'));
    }
    /**
     * @param string $path
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceUpdate($path, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('PUT', $path . '/{key}', $toController($controller, 'update'));
    }
    /**
     * @param string $path
     * @param string $controller
     * @param array $options
     */
    public function register($path, $controller, array $options = []) {
        foreach($this->getResourceMethods($this->defaults, $options) as $method) {
            $this->{'addResource' . ucfirst($method)}($path, $controller);
        }
    }
}