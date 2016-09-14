<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-12 17:20
 */
namespace Notadd\Foundation\Routing\Registrars;
use Illuminate\Support\Str;
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
     * @var array|string
     */
    protected $parameters;
    /**
     * @var \Notadd\Foundation\Routing\RouteCollector
     */
    protected $router;
    /**
     * @var bool
     */
    protected static $singularParameters = true;
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
     * @param string $name
     * @param string $base
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceCreate($name, $base, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('GET', $name . '/create', $toController($controller, 'create'));
    }
    /**
     * @param string $name
     * @param string $base
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceDestroy($name, $base, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('DELETE', $name . '/{' . $base . '}', $toController($controller, 'destroy'));
    }
    /**
     * @param string $name
     * @param string $base
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceEdit($name, $base, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('GET', $name . '/{' . $base . '}/edit', $toController($controller, 'edit'));
    }
    /**
     * @param string $name
     * @param string $base
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceIndex($name, $base, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('GET', $name, $toController($controller, 'index'));
    }
    /**
     * @param string $name
     * @param string $base
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceShow($name, $base, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('GET', $name . '/{' . $base . '}', $toController($controller, 'show'));
    }
    /**
     * @param string $name
     * @param string $base
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceStore($name, $base, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute('POST', $name, $toController($controller, 'store'));
    }
    /**
     * @param string $name
     * @param string $base
     * @param string $controller
     * @return \Notadd\Foundation\Routing\RouteCollector
     */
    public function addResourceUpdate($name, $base, $controller) {
        $toController = $this->getHandlerGenerator();
        return $this->router->addRoute([
            'PUT',
            'PATCH'
        ], $name . '/{' . $base . '}', $toController($controller, 'update'));
    }
    /**
     * @param $value
     * @return mixed
     */
    public function getResourceWildcard($value) {
        if(isset($this->parameters[$value])) {
            $value = $this->parameters[$value];
        } elseif(static::$singularParameters) {
            $value = Str::singular($value) . ':\d+';
        }
        return str_replace('-', '_', $value);
    }
    /**
     * @param string $name
     * @param string $controller
     * @param array $options
     */
    public function register($name, $controller, array $options = []) {
        if(isset($options['parameters']) && !isset($this->parameters)) {
            $this->parameters = $options['parameters'];
        }
        $base = $this->getResourceWildcard(last(explode('.', last(explode('/', $name)))));
        foreach($this->getResourceMethods($this->defaults, $options) as $method) {
            $this->{'addResource' . ucfirst($method)}($name, $base, $controller);
        }
    }
}