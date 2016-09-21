<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-20 10:56
 */
namespace Notadd\Foundation\Routing;
use Closure;
use FastRoute\Dispatcher as RouteDispatcher;
use FastRoute\RouteCollector;
use HttpResponseException;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Illuminate\Pipeline\Pipeline;
use Notadd\Foundation\Http\Contracts\Controller;
use Notadd\Foundation\Http\Exceptions\MethodNotAllowedException;
use Notadd\Foundation\Http\Exceptions\RouteNotFoundException;
use Notadd\Foundation\Routing\Registrars\ResourceRegistrar;
use Psr\Http\Message\ServerRequestInterface;
/**
 * Class Router
 * @package Notadd\Foundation\Routing
 */
class Router {
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;
    /**
     * @var array
     */
    protected $currentRoute;
    /**
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * @var array|null
     */
    protected $groupAttributes;
    /**
     * @var array
     */
    protected $namedRoutes = [];
    /**
     * @var array
     */
    protected $routeMiddleware = [];
    /**
     * @var array
     */
    protected $routes = [];
    /**
     * Router constructor.
     * @param \Illuminate\Container\Container $container
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function __construct(Container $container, EventsDispatcher $events) {
        $this->container = $container;
        $this->events = $events;
    }
    /**
     * @param array $attributes
     * @param \Closure $callback
     * @return void
     */
    public function group(array $attributes, Closure $callback) {
        $parentGroupAttributes = $this->groupAttributes;
        if(isset($attributes['middleware']) && is_string($attributes['middleware'])) {
            $attributes['middleware'] = explode('|', $attributes['middleware']);
        }
        $this->groupAttributes = $attributes;
        call_user_func($callback, $this);
        $this->groupAttributes = $parentGroupAttributes;
    }
    /**
     * @param string $uri
     * @param mixed $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function get($uri, $action) {
        $this->addRoute('GET', $uri, $action);
        return $this;
    }
    /**
     * @param string $uri
     * @param mixed $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function post($uri, $action) {
        $this->addRoute('POST', $uri, $action);
        return $this;
    }
    /**
     * @param string $uri
     * @param mixed $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function put($uri, $action) {
        $this->addRoute('PUT', $uri, $action);
        return $this;
    }
    /**
     * @param string $uri
     * @param mixed $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function patch($uri, $action) {
        $this->addRoute('PATCH', $uri, $action);
        return $this;
    }
    /**
     * @param string $uri
     * @param mixed $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function delete($uri, $action) {
        $this->addRoute('DELETE', $uri, $action);
        return $this;
    }
    /**
     * @param string $uri
     * @param mixed $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function options($uri, $action) {
        $this->addRoute('OPTIONS', $uri, $action);
        return $this;
    }
    /**
     * @param string $path
     * @param string $controller
     * @param array $options
     */
    public function resource($path, $controller, array $options = []) {
        if($this->container->bound(ResourceRegistrar::class)) {
            $registrar = $this->container->make(ResourceRegistrar::class);
        } else {
            $registrar = new ResourceRegistrar($this->container, $this);
        }
        $registrar->register($path, $controller, $options);
    }
    /**
     * @param string $method
     * @param string $uri
     * @param mixed $action
     */
    public function addRoute($method, $uri, $action) {
        $action = $this->parseAction($action);
        if(isset($this->groupAttributes)) {
            if(isset($this->groupAttributes['prefix'])) {
                $uri = trim($this->groupAttributes['prefix'], '/') . '/' . trim($uri, '/');
            }
            if(isset($this->groupAttributes['suffix'])) {
                $uri = trim($uri, '/') . rtrim($this->groupAttributes['suffix'], '/');
            }
            $action = $this->mergeGroupAttributes($action);
        }
        $uri = '/' . trim($uri, '/');
        if(isset($action['as'])) {
            $this->namedRoutes[$action['as']] = $uri;
        }
        $this->routes[$method . $uri] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action
        ];
    }
    /**
     * @return \FastRoute\Dispatcher
     */
    public function createDispatcher() {
        return $this->dispatcher ?: \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            foreach($this->routes as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        });
    }
    /**
     * @param \FastRoute\Dispatcher $dispatcher
     */
    public function setDispatcher(RouteDispatcher $dispatcher) {
        $this->dispatcher = $dispatcher;
    }
    /**
     * @param mixed $action
     * @return array
     */
    protected function parseAction($action) {
        if(is_string($action)) {
            return ['uses' => $action];
        } elseif(!is_array($action)) {
            return [$action];
        }
        if(isset($action['middleware']) && is_string($action['middleware'])) {
            $action['middleware'] = explode('|', $action['middleware']);
        }
        return $action;
    }
    /**
     * @param array $action
     * @return array
     */
    protected function mergeGroupAttributes(array $action) {
        return $this->mergeNamespaceGroup($this->mergeMiddlewareGroup($action));
    }
    /**
     * @param array $action
     * @return array
     */
    protected function mergeNamespaceGroup(array $action) {
        if(isset($this->groupAttributes['namespace']) && isset($action['uses'])) {
            $action['uses'] = $this->groupAttributes['namespace'] . '\\' . $action['uses'];
        }
        return $action;
    }
    /**
     * @param array $action
     * @return array
     */
    protected function mergeMiddlewareGroup($action) {
        if(isset($this->groupAttributes['middleware'])) {
            if(isset($action['middleware'])) {
                $action['middleware'] = array_merge($this->groupAttributes['middleware'], $action['middleware']);
            } else {
                $action['middleware'] = $this->groupAttributes['middleware'];
            }
        }
        return $action;
    }
    /**
     * @param array $middleware
     * @return $this
     */
    public function routeMiddleware(array $middleware) {
        $this->routeMiddleware = array_merge($this->routeMiddleware, $middleware);
        return $this;
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return mixed
     */
    public function dispatch(ServerRequestInterface $request) {
        $method = $request->getMethod();
        $pathInfo = $request->getUri()->getPath() ?: '/';
        if(isset($this->routes[$method . $pathInfo])) {
            return $this->handleFoundRoute([
                true,
                $this->routes[$method . $pathInfo]['action'],
                []
            ]);
        }
        return $this->handleDispatcherResponse($this->createDispatcher()->dispatch($method, $pathInfo));
    }
    /**
     * @param array $routeInfo
     * @return mixed
     */
    public function handleFoundRoute(array $routeInfo) {
        $this->currentRoute = $routeInfo;
        $action = $routeInfo[1];
        if(isset($action['middleware'])) {
            $middleware = $this->gatherMiddlewareClassNames($action['middleware']);
            return $this->sendThroughPipeline($middleware, function () use ($routeInfo) {
                return $this->callActionOnArrayBasedRoute($routeInfo);
            });
        }
        return $this->callActionOnArrayBasedRoute($routeInfo);
    }
    /**
     * @param array $routeInfo
     * @return mixed
     */
    protected function callActionOnArrayBasedRoute($routeInfo) {
        $action = $routeInfo[1];
        if(isset($action['uses'])) {
            return $this->callControllerAction($routeInfo);
        }
        foreach($action as $value) {
            if($value instanceof Closure) {
                $closure = $value;
                break;
            }
        }
        try {
            return $this->container->call($closure, $routeInfo[2]);
        } catch(HttpResponseException $e) {
            return $e;
        }
    }
    /**
     * @param array $routeInfo
     * @return mixed
     * @throws \Notadd\Foundation\Http\Exceptions\RouteNotFoundException
     */
    protected function callControllerAction($routeInfo) {
        list($controller, $method) = explode('@', $routeInfo[1]['uses']);
        if(!method_exists($instance = $this->container->make($controller), $method)) {
            throw new RouteNotFoundException;
        }
        if($instance instanceof Controller) {
            return $this->callNotaddController($instance, $method, $routeInfo);
        } else {
            return $this->callControllerCallable([
                $instance,
                $method
            ], $routeInfo[2]);
        }
    }
    /**
     * @param \Notadd\Foundation\Http\Contracts\Controller $instance
     * @param string $method
     * @param array $routeInfo
     * @return mixed
     */
    protected function callNotaddController(Controller $instance, $method, $routeInfo) {
        $middleware = $instance->getMiddlewareForMethod($method);
        if(count($middleware) > 0) {
            return $this->callNotaddControllerWithMiddleware($instance, $method, $routeInfo, $middleware);
        } else {
            return $this->callControllerCallable([
                $instance,
                $method
            ], $routeInfo[2]);
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
        $middleware = $this->gatherMiddlewareClassNames($middleware);
        return $this->sendThroughPipeline($middleware, function () use ($instance, $method, $routeInfo) {
            return $this->callControllerCallable([
                $instance,
                $method
            ], $routeInfo[2]);
        });
    }
    /**
     * @param callable $callable
     * @param array $parameters
     * @return mixed
     */
    protected function callControllerCallable(callable $callable, array $parameters = []) {
        try {
            return $this->container->call($callable, $parameters);
        } catch(HttpResponseException $e) {
            return $e;
        }
    }
    /**
     * @param array $middleware
     * @param \Closure $then
     * @return mixed
     */
    protected function sendThroughPipeline(array $middleware, Closure $then) {
        $shouldSkipMiddleware = $this->container->bound('middleware.disable') && $this->container->make('middleware.disable') === true;
        if(count($middleware) > 0 && !$shouldSkipMiddleware) {
            return (new Pipeline($this->container))->send($this->container->make(ServerRequestInterface::class))->through($middleware)->then($then);
        }
        return $then();
    }
    /**
     * @param $routeInfo
     * @return mixed
     * @throws \Notadd\Foundation\Http\Exceptions\MethodNotAllowedException
     * @throws \Notadd\Foundation\Http\Exceptions\RouteNotFoundException
     */
    protected function handleDispatcherResponse($routeInfo) {
        switch($routeInfo[0]) {
            case RouteDispatcher::NOT_FOUND:
                throw new RouteNotFoundException;
            case RouteDispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException;
            case RouteDispatcher::FOUND:
                return $this->handleFoundRoute($routeInfo);
        }
        return null;
    }
    /**
     * @param $middleware
     * @return array
     */
    protected function gatherMiddlewareClassNames($middleware) {
        $middleware = is_string($middleware) ? explode('|', $middleware) : (array)$middleware;
        return array_map(function ($name) {
            list($name, $parameters) = array_pad(explode(':', $name, 2), 2, null);
            return array_get($this->routeMiddleware, $name, $name) . ($parameters ? ':' . $parameters : '');
        }, $middleware);
    }
}