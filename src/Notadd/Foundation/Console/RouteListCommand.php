<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 16:19
 */
namespace Notadd\Foundation\Console;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Notadd\Foundation\Routing\AbstractController;
use Symfony\Component\Console\Input\InputOption;
/**
 * Class RouteListCommand
 * @package Notadd\Foundation\Console
 */
class RouteListCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'route:list';
    /**
     * @var string
     */
    protected $description = 'List all registered routes';
    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;
    /**
     * @var \Illuminate\Routing\RouteCollection
     */
    protected $routes;
    /**
     * @var array
     */
    protected $headers = [
        'Domain',
        'Method',
        'URI',
        'Name',
        'Action',
        'Middleware'
    ];
    /**
     * RouteListCommand constructor.
     * @param \Illuminate\Routing\Router $router
     */
    public function __construct(Router $router) {
        parent::__construct();
        $this->router = $router;
        $this->routes = $router->getRoutes();
    }
    /**
     * @return void
     */
    public function fire() {
        if(count($this->routes) == 0) {
            return $this->error("Your application doesn't have any routes.");
        }
        $this->displayRoutes($this->getRoutes());
    }
    /**
     * @return array
     */
    protected function getRoutes() {
        $results = [];
        foreach($this->routes as $route) {
            $results[] = $this->getRouteInformation($route);
        }
        if($sort = $this->option('sort')) {
            $results = array_sort($results, function ($value) use ($sort) {
                return $value[$sort];
            });
        }
        if($this->option('reverse')) {
            $results = array_reverse($results);
        }
        return array_filter($results);
    }
    /**
     * @param \Illuminate\Routing\Route $route
     * @return array|void
     */
    protected function getRouteInformation(Route $route) {
        return $this->filterRoute([
            'host' => $route->domain(),
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
            'middleware' => $this->getMiddleware($route),
        ]);
    }
    /**
     * @param array $routes
     */
    protected function displayRoutes(array $routes) {
        $this->table($this->headers, $routes);
    }
    /**
     * @param $route
     * @return string
     */
    protected function getMiddleware($route) {
        $middlewares = array_values($route->middleware());
        $middlewares = array_unique(array_merge($middlewares, $this->getPatternFilters($route)));
        $actionName = $route->getActionName();
        if(!empty($actionName) && $actionName !== 'Closure') {
            $middlewares = array_merge($middlewares, $this->getControllerMiddleware($actionName));
        }
        return implode(',', $middlewares);
    }
    /**
     * @param $actionName
     * @return array
     */
    protected function getControllerMiddleware($actionName) {
        AbstractController::setRouter($this->laravel['router']);
        $segments = explode('@', $actionName);
        return $this->getControllerMiddlewareFromInstance($this->laravel->make($segments[0]), $segments[1]);
    }
    /**
     * @param $controller
     * @param $method
     * @return array
     */
    protected function getControllerMiddlewareFromInstance($controller, $method) {
        $middleware = $this->router->getMiddleware();
        $results = [];
        foreach($controller->getMiddleware() as $name => $options) {
            if(!$this->methodExcludedByOptions($method, $options)) {
                $results[] = Arr::get($middleware, $name, $name);
            }
        }
        return $results;
    }
    /**
     * @param $method
     * @param array $options
     * @return bool
     */
    protected function methodExcludedByOptions($method, array $options) {
        return (!empty($options['only']) && !in_array($method, (array)$options['only'])) || (!empty($options['except']) && in_array($method, (array)$options['except']));
    }
    /**
     * @param $route
     * @return array
     */
    protected function getPatternFilters($route) {
        $patterns = [];
        foreach($route->methods() as $method) {
            $inner = $this->getMethodPatterns($route->uri(), $method);
            $patterns = array_merge($patterns, array_keys($inner));
        }
        return $patterns;
    }
    /**
     * @param $uri
     * @param $method
     * @return array
     */
    protected function getMethodPatterns($uri, $method) {
        return $this->router->findPatternFilters(Request::create($uri, $method));
    }
    /**
     * @param array $route
     * @return array|void
     */
    protected function filterRoute(array $route) {
        if(($this->option('name') && !Str::contains($route['name'], $this->option('name'))) || $this->option('path') && !Str::contains($route['uri'], $this->option('path')) || $this->option('method') && !Str::contains($route['method'], $this->option('method'))) {
            return;
        }
        return $route;
    }
    /**
     * @return array
     */
    protected function getOptions() {
        return [
            [
                'method',
                null,
                InputOption::VALUE_OPTIONAL,
                'Filter the routes by method.'
            ],
            [
                'name',
                null,
                InputOption::VALUE_OPTIONAL,
                'Filter the routes by name.'
            ],
            [
                'path',
                null,
                InputOption::VALUE_OPTIONAL,
                'Filter the routes by path.'
            ],
            [
                'reverse',
                'r',
                InputOption::VALUE_NONE,
                'Reverse the ordering of the routes.'
            ],
            [
                'sort',
                null,
                InputOption::VALUE_OPTIONAL,
                'The column (host, method, uri, name, action, middleware) to sort by.',
                'uri'
            ],
        ];
    }
}