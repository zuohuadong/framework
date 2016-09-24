<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 19:03
 */
namespace Notadd\Foundation\Auth;
use Closure;
use InvalidArgumentException;
use Illuminate\Contracts\Auth\Factory as FactoryContract;
use Notadd\Foundation\Application;
use Notadd\Foundation\Auth\Guards\RequestGuard;
use Notadd\Foundation\Auth\Guards\SessionGuard;
use Notadd\Foundation\Auth\Guards\TokenGuard;
use Notadd\Foundation\Auth\Traits\CreatesUserProviders;
/**
 * Class AuthManager
 * @package Notadd\Foundation\Auth
 */
class AuthManager implements FactoryContract {
    use CreatesUserProviders;
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $app;
    /**
     * @var array
     */
    protected $customCreators = [];
    /**
     * @var array
     */
    protected $guards = [];
    /**
     * @var \Closure
     */
    protected $userResolver;
    /**
     * AuthManager constructor.
     * @param \Notadd\Foundation\Application $app
     */
    public function __construct(Application $app) {
        $this->app = $app;
        $this->userResolver = function ($guard = null) {
            return $this->guard($guard)->user();
        };
    }
    /**
     * @param string $name
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard($name = null) {
        $name = $name ?: $this->getDefaultDriver();
        return isset($this->guards[$name]) ? $this->guards[$name] : $this->guards[$name] = $this->resolve($name);
    }
    /**
     * @param string $name
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     * @throws \InvalidArgumentException
     */
    protected function resolve($name) {
        $config = $this->getConfig($name);
        if(is_null($config)) {
            throw new InvalidArgumentException("Auth guard [{$name}] is not defined.");
        }
        if(isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($name, $config);
        }
        $driverMethod = 'create' . ucfirst($config['driver']) . 'Driver';
        if(method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($name, $config);
        }
        throw new InvalidArgumentException("Auth guard driver [{$name}] is not defined.");
    }
    /**
     * Call a custom driver creator.
     * @param string $name
     * @param array $config
     * @return mixed
     */
    protected function callCustomCreator($name, array $config) {
        return $this->customCreators[$config['driver']]($this->app, $name, $config);
    }
    /**
     * @param string $name
     * @param array $config
     * @return \Notadd\Foundation\Auth\Guards\SessionGuard
     */
    public function createSessionDriver($name, $config) {
        $provider = $this->createUserProvider($config['provider']);
        $guard = new SessionGuard($name, $provider, $this->app['session.store']);
        if(method_exists($guard, 'setCookieJar')) {
            $guard->setCookieJar($this->app['cookie']);
        }
        if(method_exists($guard, 'setDispatcher')) {
            $guard->setDispatcher($this->app['events']);
        }
        if(method_exists($guard, 'setRequest')) {
            $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));
        }
        return $guard;
    }
    /**
     * @param string $name
     * @param array $config
     * @return \Notadd\Foundation\Auth\Guards\TokenGuard
     */
    public function createTokenDriver($name, $config) {
        $guard = new TokenGuard($this->createUserProvider($config['provider']), $this->app['request']);
        $this->app->refresh('request', $guard, 'setRequest');
        return $guard;
    }
    /**
     * @param string $name
     * @return array
     */
    protected function getConfig($name) {
        return $this->app['config']["auth.guards.{$name}"];
    }
    /**
     * @return string
     */
    public function getDefaultDriver() {
        return $this->app['config']['auth.defaults.guard'];
    }
    /**
     * @param string $name
     * @return void
     */
    public function shouldUse($name) {
        $name = $name ?: $this->getDefaultDriver();
        $this->setDefaultDriver($name);
        $this->userResolver = function ($name = null) {
            return $this->guard($name)->user();
        };
    }
    /**
     * @param string $name
     * @return void
     */
    public function setDefaultDriver($name) {
        $this->app['config']['auth.defaults.guard'] = $name;
    }
    /**
     * @param string $driver
     * @param callable $callback
     * @return $this
     */
    public function viaRequest($driver, callable $callback) {
        return $this->extend($driver, function () use ($callback) {
            $guard = new RequestGuard($callback, $this->app['request']);
            $this->app->refresh('request', $guard, 'setRequest');
            return $guard;
        });
    }
    /**
     * @return \Closure
     */
    public function userResolver() {
        return $this->userResolver;
    }
    /**
     * @param \Closure $userResolver
     * @return $this
     */
    public function resolveUsersUsing(Closure $userResolver) {
        $this->userResolver = $userResolver;
        return $this;
    }
    /**
     * @param string $driver
     * @param \Closure $callback
     * @return $this
     */
    public function extend($driver, Closure $callback) {
        $this->customCreators[$driver] = $callback;
        return $this;
    }
    /**
     * @param string $name
     * @param \Closure $callback
     * @return $this
     */
    public function provider($name, Closure $callback) {
        $this->customProviderCreators[$name] = $callback;
        return $this;
    }
    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters) {
        return $this->guard()->{$method}(...$parameters);
    }
}