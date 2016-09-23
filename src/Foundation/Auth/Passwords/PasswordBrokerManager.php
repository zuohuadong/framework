<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:29
 */
namespace Notadd\Foundation\Auth\Passwords;
use Illuminate\Contracts\Auth\PasswordBrokerFactory as FactoryContract;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Notadd\Foundation\Application;
/**
 * Class PasswordBrokerManager
 * @package Notadd\Foundation\Auth\Passwords
 */
class PasswordBrokerManager implements FactoryContract {
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $app;
    /**
     * @var array
     */
    protected $brokers = [];
    /**
     * PasswordBrokerManager constructor.
     * @param $app
     */
    public function __construct(Application $app) {
        $this->app = $app;
    }
    /**
     * @param  string $name
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker($name = null) {
        $name = $name ?: $this->getDefaultDriver();
        return isset($this->brokers[$name]) ? $this->brokers[$name] : $this->brokers[$name] = $this->resolve($name);
    }
    /**
     * @param  string $name
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     * @throws \InvalidArgumentException
     */
    protected function resolve($name) {
        $config = $this->getConfig($name);
        if(is_null($config)) {
            throw new InvalidArgumentException("Password resetter [{$name}] is not defined.");
        }
        return new PasswordBroker($this->createTokenRepository($config), $this->app['auth']->createUserProvider($config['provider']));
    }
    /**
     * @param  array $config
     * @return \Notadd\Foundation\Auth\Contracts\TokenRepository
     */
    protected function createTokenRepository(array $config) {
        $key = $this->app['config']['app.key'];
        if(Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        $connection = isset($config['connection']) ? $config['connection'] : null;
        return new DatabaseTokenRepository($this->app['db']->connection($connection), $config['table'], $key, $config['expire']);
    }
    /**
     * @param  string $name
     * @return array
     */
    protected function getConfig($name) {
        return $this->app['config']["auth.passwords.{$name}"];
    }
    /**
     * @return string
     */
    public function getDefaultDriver() {
        return $this->app['config']['auth.defaults.passwords'];
    }
    /**
     * @param  string $name
     * @return void
     */
    public function setDefaultDriver($name) {
        $this->app['config']['auth.defaults.passwords'] = $name;
    }
    /**
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters) {
        return $this->broker()->{$method}(...$parameters);
    }
}