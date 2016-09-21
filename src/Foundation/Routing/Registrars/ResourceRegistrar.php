<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-12 17:20
 */
namespace Notadd\Foundation\Routing\Registrars;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Notadd\Foundation\Routing\Router;
/**
 * Class ResourceRegistrar
 * @package Notadd\Foundation\Routing\Registrars
 */
class ResourceRegistrar {
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;
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
     * @var \Notadd\Foundation\Routing\Router
     */
    protected $router;
    /**
     * @var bool
     */
    protected static $singularParameters = true;
    /**
     * ResourceRegistrar constructor.
     * @param \Illuminate\Container\Container $container
     * @param \Notadd\Foundation\Routing\Router $router
     */
    public function __construct(Container $container, Router $router) {
        $this->container = $container;
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
     * @param array $options
     * @return \Notadd\Foundation\Routing\Router
     */
    public function addResourceCreate($name, $base, $controller, $options) {
        $action = $this->getResourceAction($name, $controller, 'create', $options);
        return $this->router->addRoute('GET', $name . '/create', $action);
    }
    /**
     * @param string $name
     * @param string $base
     * @param string $controller
     * @param array $options
     * @return \Notadd\Foundation\Routing\Router
     */
    public function addResourceDestroy($name, $base, $controller, $options) {
        $action = $this->getResourceAction($name, $controller, 'destroy', $options);
        return $this->router->addRoute('DELETE', $name . '/{' . $base . '}', $action);
    }
    /**
     * @param string $name
     * @param string $base
     * @param string $controller
     * @param array $options
     * @return \Notadd\Foundation\Routing\Router
     */
    public function addResourceEdit($name, $base, $controller, $options) {
        $action = $this->getResourceAction($name, $controller, 'edit', $options);
        return $this->router->addRoute('GET', $name . '/{' . $base . '}/edit', $action);
    }
    /**
     * @param string $name
     * @param string $base
     * @param string $controller
     * @param array $options
     * @return \Notadd\Foundation\Routing\Router
     */
    public function addResourceIndex($name, $base, $controller, $options) {
        $action = $this->getResourceAction($name, $controller, 'index', $options);
        return $this->router->addRoute('GET', $name, $action);
    }
    /**
     * @param string $name
     * @param string $base
     * @param string $controller
     * @param array $options
     * @return \Notadd\Foundation\Routing\Router
     */
    public function addResourceShow($name, $base, $controller, $options) {
        $action = $this->getResourceAction($name, $controller, 'show', $options);
        return $this->router->addRoute('GET', $name . '/{' . $base . '}', $action);
    }
    /**
     * @param string $name
     * @param string $base
     * @param string $controller
     * @param array $options
     * @return \Notadd\Foundation\Routing\Router
     */
    public function addResourceStore($name, $base, $controller, $options) {
        $action = $this->getResourceAction($name, $controller, 'store', $options);
        return $this->router->addRoute('POST', $name, $action);
    }
    /**
     * @param string $name
     * @param string $base
     * @param string $controller
     * @param array $options
     * @return \Notadd\Foundation\Routing\Router
     */
    public function addResourceUpdate($name, $base, $controller, $options) {
        $action = $this->getResourceAction($name, $controller, 'update', $options);
        return $this->router->addRoute('PUT', $name . '/{' . $base . '}', $action);
    }
    /**
     * Get the action array for a resource route.
     * @param string $resource
     * @param string $controller
     * @param string $method
     * @param array $options
     * @return array
     */
    protected function getResourceAction($resource, $controller, $method, $options) {
        $name = $this->getResourceName($resource, $method, $options);
        return [
            'as' => $name,
            'uses' => $controller . '@' . $method
        ];
    }
    /**
     * @param  string $resource
     * @param  string $method
     * @param  array $options
     * @return string
     */
    protected function getResourceName($resource, $method, $options) {
        $prefix = isset($options['as']) ? $options['as'] . '.' : '';
        return $prefix . $resource . '.' . $method;
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
            $this->{'addResource' . ucfirst($method)}($name, $base, $controller, $options);
        }
    }
}