<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-16 21:44
 */
namespace Notadd\Foundation\Http;
use Exception;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel as KernelContract;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Router;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Notadd\Admin\Middleware\AuthenticateWithAdmin;
use Notadd\Admin\Middleware\RedirectIfAuthenticated as AdminRedirectIfAuthenticated;
use Notadd\Foundation\Bootstrap\BootProviders;
use Notadd\Foundation\Bootstrap\ConfigureLogging;
use Notadd\Foundation\Bootstrap\HandleExceptions;
use Notadd\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Notadd\Foundation\Http\Middleware\VerifyCsrfToken;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;
/**
 * Class Kernel
 * @package Notadd\Foundation\Http
 */
class Kernel implements KernelContract {
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;
    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;
    /**
     * @var array
     */
    protected $bootstrappers = [
        ConfigureLogging::class,
        HandleExceptions::class,
        BootProviders::class,
    ];
    /**
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
    ];
    /**
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
        ],
        'api' => [
            'throttle:60,1',
        ],
    ];
    /**
     * @var array
     */
    protected $routeMiddleware = [
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'auth.admin' => AuthenticateWithAdmin::class,
        'guest.admin' => AdminRedirectIfAuthenticated::class,
    ];
    /**
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Routing\Router $router
     */
    public function __construct(Application $app, Router $router) {
        $this->app = $app;
        $this->router = $router;
        foreach($this->routeMiddleware as $key => $middleware) {
            $router->middleware($key, $middleware);
        }
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function handle($request) {
        try {
            $request->enableHttpMethodParameterOverride();
            $response = $this->sendRequestThroughRouter($request);
        } catch(Exception $e) {
            $this->reportException($e);
            $response = $this->renderException($request, $e);
        } catch(Throwable $e) {
            $e = new FatalThrowableError($e);
            $this->reportException($e);
            $response = $this->renderException($request, $e);
        }
        $this->app->make('events')->fire('kernel.handled', [
            $request,
            $response
        ]);
        return $response;
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendRequestThroughRouter($request) {
        $this->app->instance('request', $request);
        $this->bootstrap();
        return (new Pipeline($this->app))->send($request)->through($this->app->shouldSkipMiddleware() ? [] : $this->middleware)->then($this->dispatchToRouter());
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     * @return void
     */
    public function terminate($request, $response) {
        $middlewares = $this->app->shouldSkipMiddleware() ? [] : array_merge($this->gatherRouteMiddlewares($request), $this->middleware);
        foreach($middlewares as $middleware) {
            list($name, $parameters) = $this->parseMiddleware($middleware);
            $instance = $this->app->make($name);
            if(method_exists($instance, 'terminate')) {
                $instance->terminate($request, $response);
            }
        }
        $this->app->terminate();
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function gatherRouteMiddlewares($request) {
        if($request->route()) {
            return $this->router->gatherRouteMiddlewares($request->route());
        }
        return [];
    }
    /**
     * @param string $middleware
     * @return array
     */
    protected function parseMiddleware($middleware) {
        list($name, $parameters) = array_pad(explode(':', $middleware, 2), 2, []);
        if(is_string($parameters)) {
            $parameters = explode(',', $parameters);
        }
        return [
            $name,
            $parameters
        ];
    }
    /**
     * @param string $middleware
     * @return $this
     */
    public function prependMiddleware($middleware) {
        if(array_search($middleware, $this->middleware) === false) {
            array_unshift($this->middleware, $middleware);
        }
        return $this;
    }
    /**
     * @param string $middleware
     * @return $this
     */
    public function pushMiddleware($middleware) {
        if(array_search($middleware, $this->middleware) === false) {
            $this->middleware[] = $middleware;
        }
        return $this;
    }
    /**
     * @return void
     */
    public function bootstrap() {
        if(!$this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers());
        }
    }
    /**
     * @return \Closure
     */
    protected function dispatchToRouter() {
        return function ($request) {
            $this->app->instance('request', $request);
            return $this->router->dispatch($request);
        };
    }
    /**
     * @param string $middleware
     * @return bool
     */
    public function hasMiddleware($middleware) {
        return array_key_exists($middleware, array_flip($this->middleware));
    }
    /**
     * @return array
     */
    protected function bootstrappers() {
        return $this->bootstrappers;
    }
    /**
     * @param \Exception $e
     * @return void
     */
    protected function reportException(Exception $e) {
        $this->app->make('Illuminate\Contracts\Debug\ExceptionHandler')->report($e);
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderException($request, Exception $e) {
        return $this->app->make('Illuminate\Contracts\Debug\ExceptionHandler')->render($request, $e);
    }
    /**
     * @return \Illuminate\Contracts\Foundation\Application
     */
    public function getApplication() {
        return $this->app;
    }
}