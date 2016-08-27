<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-19 22:55
 */
namespace Notadd\Foundation;
use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
/**
 * Class Application
 * @package Notadd\Foundation
 */
class Application extends Container implements ApplicationContract {
    /**
     * @var string
     */
    const VERSION = '0.1.8.3';
    /**
     * @var string
     */
    protected $basePath;
    /**
     * @var bool
     */
    protected $booted = false;
    /**
     * @var array
     */
    protected $bootingCallbacks = [];
    /**
     * @var array
     */
    protected $bootedCallbacks = [];
    /**
     * @var array
     */
    protected $serviceProviders = [];
    /**
     * @var array
     */
    protected $loadedProviders = [];
    /**
     * @var array
     */
    protected $deferredServices = [];
    /**
     * @var string
     */
    protected $storagePath;
    /**
     * Application constructor.
     * @param null $basePath
     */
    public function __construct($basePath = null) {
        $this->registerBaseBindings();
        $this->registerBaseServiceProviders();
        $this->registerCoreContainerAliases();
        if($basePath) {
            $this->setBasePath($basePath);
        }
    }
    /**
     * @return string
     */
    public function basePath() {
        return $this->basePath;
    }
    /**
     * @return void
     */
    protected function bindPathsInContainer() {
        foreach([
                    'base',
                    'public',
                    'storage'
                ] as $path) {
            $this->instance('path.' . $path, $this->{$path . 'Path'}());
        }
    }
    /**
     * @return void
     */
    public function boot() {
        if($this->booted) {
            return;
        }
        $this->fireAppCallbacks($this->bootingCallbacks);
        array_walk($this->serviceProviders, function ($p) {
            $this->bootProvider($p);
        });
        $this->booted = true;
        $this->fireAppCallbacks($this->bootedCallbacks);
    }
    /**
     * @param  mixed $callback
     * @return void
     */
    public function booted($callback) {
        $this->bootedCallbacks[] = $callback;
        if($this->isBooted()) {
            $this->fireAppCallbacks([$callback]);
        }
    }
    /**
     * @param  mixed $callback
     * @return void
     */
    public function booting($callback) {
        $this->bootingCallbacks[] = $callback;
    }
    /**
     * @param \Illuminate\Support\ServiceProvider $provider
     * @return mixed
     */
    protected function bootProvider(ServiceProvider $provider) {
        if(method_exists($provider, 'boot')) {
            return $this->call([
                $provider,
                'boot'
            ]);
        }
    }
    /**
     * @param  mixed
     * @return string
     */
    public function environment() {
        if(func_num_args() > 0) {
            $patterns = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();
            foreach($patterns as $pattern) {
                if(Str::is($pattern, $this['env'])) {
                    return true;
                }
            }
            return false;
        }
        return $this['env'];
    }
    /**
     * @param array $callbacks
     */
    protected function fireAppCallbacks(array $callbacks) {
        foreach($callbacks as $callback) {
            call_user_func($callback, $this);
        }
    }
    /**
     *
     */
    public function flush() {
        parent::flush();
        $this->loadedProviders = [];
    }
    /**
     * @return string
     */
    public function getCachedCompilePath() {
        return $this->basePath() . '/bootstrap/cache/compiled.php';
    }
    /**
     * @return string
     */
    public function getCachedServicesPath() {
        return $this->basePath() . '/bootstrap/cache/services.json';
    }
    /**
     * @param $provider
     * @return mixed
     */
    public function getProvider($provider) {
        $name = is_string($provider) ? $provider : get_class($provider);
        return Arr::first($this->serviceProviders, function ($key, $value) use ($name) {
            return $value instanceof $name;
        });
    }
    /**
     * @return bool
     */
    public function isBooted() {
        return $this->booted;
    }
    /**
     * @return bool
     */
    public function isDownForMaintenance() {
    }
    /**
     * @return bool
     */
    public function isInstalled() {
        return false;
    }
    /**
     * @param $service
     */
    public function loadDeferredProvider($service) {
        if(!isset($this->deferredServices[$service])) {
            return;
        }
        $provider = $this->deferredServices[$service];
        if(!isset($this->loadedProviders[$provider])) {
            $this->registerDeferredProvider($provider, $service);
        }
    }
    /**
     * @param $provider
     */
    protected function markAsRegistered($provider) {
        $this['events']->fire($class = get_class($provider), [$provider]);
        $this->serviceProviders[] = $provider;
        $this->loadedProviders[$class] = true;
    }
    /**
     * @return string
     */
    public function publicPath() {
        return $this->basePath . DIRECTORY_SEPARATOR . 'public';
    }
    /**
     * @param  \Illuminate\Support\ServiceProvider|string $provider
     * @param  array $options
     * @param  bool $force
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register($provider, $options = [], $force = false) {
        if($registered = $this->getProvider($provider) && !$force) {
            return $registered;
        }
        if(is_string($provider)) {
            $provider = $this->resolveProviderClass($provider);
        }
        $provider->register();
        foreach($options as $key => $value) {
            $this[$key] = $value;
        }
        $this->markAsRegistered($provider);
        if($this->booted) {
            $this->bootProvider($provider);
        }
        return $provider;
    }
    /**
     * @return void
     */
    protected function registerBaseBindings() {
        static::setInstance($this);
        $this->instance('app', $this);
        $this->instance('Illuminate\Container\Container', $this);
    }
    /**
     * @return void
     */
    public function registerConfiguredProviders() {
    }
    /**
     * @param  string $provider
     * @param  string $service
     * @return void
     */
    public function registerDeferredProvider($provider, $service = null) {
        if($service) {
            unset($this->deferredServices[$service]);
        }
        $this->register($instance = new $provider($this));
        if(!$this->booted) {
            $this->booting(function () use ($instance) {
                $this->bootProvider($instance);
            });
        }
    }
    /**
     * @return void
     */
    protected function registerBaseServiceProviders() {
        $this->register(new EventServiceProvider($this));
    }
    /**
     * @return void
     */
    public function registerCoreContainerAliases() {
        $aliases = [
            'app' => [
                Application::class,
                'Illuminate\Contracts\Container\Container',
                'Illuminate\Contracts\Foundation\Application'
            ],
            'blade.compiler' => 'Illuminate\View\Compilers\BladeCompiler',
            'cache' => [
                'Illuminate\Cache\CacheManager',
                'Illuminate\Contracts\Cache\Factory'
            ],
            'cache.store' => [
                'Illuminate\Cache\Repository',
                'Illuminate\Contracts\Cache\Repository'
            ],
            'config' => [
                'Illuminate\Config\Repository',
                'Illuminate\Contracts\Config\Repository'
            ],
            'db' => 'Illuminate\Database\DatabaseManager',
            'events' => [
                'Illuminate\Events\Dispatcher',
                'Illuminate\Contracts\Events\Dispatcher'
            ],
            'files' => 'Illuminate\Filesystem\Filesystem',
            'filesystem' => [
                'Illuminate\Filesystem\FilesystemManager',
                'Illuminate\Contracts\Filesystem\Factory'
            ],
            'filesystem.disk' => 'Illuminate\Contracts\Filesystem\Filesystem',
            'filesystem.cloud' => 'Illuminate\Contracts\Filesystem\Cloud',
            'hash' => 'Illuminate\Contracts\Hashing\Hasher',
            'mailer' => [
                'Illuminate\Mail\Mailer',
                'Illuminate\Contracts\Mail\Mailer',
                'Illuminate\Contracts\Mail\MailQueue'
            ],
            'validator' => [
                'Illuminate\Validation\Factory',
                'Illuminate\Contracts\Validation\Factory'
            ],
            'view' => [
                'Illuminate\View\Factory',
                'Illuminate\Contracts\View\Factory'
            ],
        ];
        foreach($aliases as $key => $aliases) {
            foreach((array)$aliases as $alias) {
                $this->alias($key, $alias);
            }
        }
    }
    /**
     * @param $provider
     * @return mixed
     */
    public function resolveProviderClass($provider) {
        return new $provider($this);
    }
    /**
     * @param string $basePath
     * @return $this
     */
    public function setBasePath($basePath) {
        $this->basePath = rtrim($basePath, '\/');
        $this->bindPathsInContainer();
        return $this;
    }
    /**
     * @return string
     */
    public function storagePath() {
        return $this->storagePath ?: $this->basePath . DIRECTORY_SEPARATOR . 'storage';
    }
    /**
     * @param $path
     * @return $this
     */
    public function useStoragePath($path) {
        $this->storagePath = $path;
        $this->instance('path.storage', $path);
        return $this;
    }
    /**
     * @return string
     */
    public function version() {
        return static::VERSION;
    }
}