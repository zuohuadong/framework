<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:33
 */
namespace Notadd\Foundation\Auth\Traits;
use InvalidArgumentException;
use Notadd\Foundation\Auth\Providers\DatabaseUserProvider;
use Notadd\Foundation\Auth\Providers\EloquentUserProvider;
/**
 * Class CreatesUserProviders
 * @package Notadd\Foundation\Auth\Traits
 */
trait CreatesUserProviders {
    /**
     * @var array
     */
    protected $customProviderCreators = [];
    /**
     * @param  string $provider
     * @return \Illuminate\Contracts\Auth\UserProvider
     * @throws \InvalidArgumentException
     */
    public function createUserProvider($provider) {
        $config = $this->app['config']['auth.providers.' . $provider];
        if(isset($this->customProviderCreators[$config['driver']])) {
            return call_user_func($this->customProviderCreators[$config['driver']], $this->app, $config);
        }
        switch($config['driver']) {
            case 'database':
                return $this->createDatabaseProvider($config);
            case 'eloquent':
                return $this->createEloquentProvider($config);
            default:
                throw new InvalidArgumentException("Authentication user provider [{$config['driver']}] is not defined.");
        }
    }
    /**
     * @param  array $config
     * @return \Notadd\Foundation\Auth\Providers\DatabaseUserProvider
     */
    protected function createDatabaseProvider($config) {
        $connection = $this->app['db']->connection();
        return new DatabaseUserProvider($connection, $this->app['hash'], $config['table']);
    }
    /**
     * @param  array $config
     * @return \Notadd\Foundation\Auth\Providers\EloquentUserProvider
     */
    protected function createEloquentProvider($config) {
        return new EloquentUserProvider($this->app['hash'], $config['model']);
    }
}