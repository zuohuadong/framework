<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-16 21:20
 */
namespace Notadd\Foundation;
use Closure;
use Notadd\Auth\AuthServiceProvider;
use Notadd\Foundation\SearchEngine\SearchEngineServiceProvider;
use RuntimeException;
use Illuminate\Auth\Passwords\PasswordResetServiceProvider;
use Illuminate\Broadcasting\BroadcastServiceProvider;
use Illuminate\Bus\BusServiceProvider;
use Illuminate\Console\ScheduleServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Cookie\CookieServiceProvider;
use Illuminate\Encryption\EncryptionServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Pagination\PaginationServiceProvider;
use Illuminate\Pipeline\PipelineServiceProvider;
use Illuminate\Queue\QueueServiceProvider;
use Illuminate\Redis\RedisServiceProvider;
use Illuminate\Routing\ControllerServiceProvider;
use Illuminate\Routing\GeneratorServiceProvider;
use Illuminate\Session\SessionServiceProvider;
use Illuminate\View\ViewServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Routing\RoutingServiceProvider;
use Notadd\Cache\CacheServiceProvider;
use Notadd\Foundation\Composer\ComposerServiceProvider;
use Notadd\Foundation\Console\ConsoleServiceProvider;
use Notadd\Foundation\Console\ConsoleSupportServiceProvider;
use Notadd\Foundation\Database\DatabaseServiceProvider;
use Notadd\Foundation\Extension\ExtensionServiceProvider;
use Notadd\Foundation\Http\FormRequestServiceProvider;
use Notadd\Foundation\Translation\TranslationServiceProvider;
use Notadd\Foundation\Validation\ValidationServiceProvider;
use Notadd\Setting\SettingServiceProvider;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/**
 * Class Application
 * @package Notadd\Foundation
 */
class Application extends Container implements ApplicationContract, HttpKernelInterface {
    /**
     * @var string
     */
    const VERSION = '0.1.6';
    /**
     * @var string
     */
    protected $basePath;
    /**
     * @var bool
     */
    protected $hasBeenBootstrapped = false;
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
    protected $terminatingCallbacks = [];
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
     * @var callable|null
     */
    protected $monologConfigurator;
    /**
     * @var string
     */
    protected $storagePath;
    /**
     * The application namespace.
     * @var string
     */
    protected $namespace = null;
    /**
     * Application constructor.
     * @param string|null $basePath
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
    public function version() {
        return static::VERSION;
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
    protected function registerBaseServiceProviders() {
        $this->register(new EventServiceProvider($this));
        $this->register(new RoutingServiceProvider($this));
    }
    /**
     * @param array $bootstrappers
     * @return void
     */
    public function bootstrapWith(array $bootstrappers) {
        $this->hasBeenBootstrapped = true;
        foreach($bootstrappers as $bootstrapper) {
            $this['events']->fire('bootstrapping: ' . $bootstrapper, [$this]);
            $this->make($bootstrapper)->bootstrap($this);
            $this['events']->fire('bootstrapped: ' . $bootstrapper, [$this]);
        }
    }
    /**
     * @param \Closure $callback
     * @return void
     */
    public function afterLoadingEnvironment(Closure $callback) {
        return $this->afterBootstrapping('Illuminate\Foundation\Bootstrap\DetectEnvironment', $callback);
    }
    /**
     * @param string $bootstrapper
     * @param Closure $callback
     * @return void
     */
    public function beforeBootstrapping($bootstrapper, Closure $callback) {
        $this['events']->listen('bootstrapping: ' . $bootstrapper, $callback);
    }
    /**
     * @param string $bootstrapper
     * @param Closure $callback
     * @return void
     */
    public function afterBootstrapping($bootstrapper, Closure $callback) {
        $this['events']->listen('bootstrapped: ' . $bootstrapper, $callback);
    }
    /**
     * @return bool
     */
    public function hasBeenBootstrapped() {
        return $this->hasBeenBootstrapped;
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
     * @return void
     */
    protected function bindPathsInContainer() {
        $this->instance('path', $this->path());
        foreach([
                    'base',
                    'config',
                    'framework',
                    'lang',
                    'public',
                    'storage',
                    'stubs'
                ] as $path) {
            $this->instance('path.' . $path, $this->{$path . 'Path'}());
        }
    }
    /**
     * @return string
     */
    public function path() {
        return $this->basePath . DIRECTORY_SEPARATOR . 'app';
    }
    /**
     * @return string
     */
    public function basePath() {
        return $this->basePath;
    }
    /**
     * @return string
     */
    public function configPath() {
        return $this->basePath . DIRECTORY_SEPARATOR . 'config';
    }
    /**
     * @return string
     */
    public function langPath() {
        return $this->basePath . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'lang';
    }
    /**
     * @return string
     */
    public function publicPath() {
        return realpath($this->basePath . DIRECTORY_SEPARATOR . 'public');
    }
    public function usePublicPath($path) {
        $this->instance('path.public', $path);
        return $this;
    }
    /**
     * @return string
     */
    public function storagePath() {
        return $this->storagePath ?: $this->basePath . DIRECTORY_SEPARATOR . 'storage';
    }
    /**
     * @param string $path
     * @return $this
     */
    public function useStoragePath($path) {
        $this->storagePath = $path;
        $this->instance('path.storage', $path);
        return $this;
    }
    public function stubsPath() {
        return realpath($this->frameworkPath() . DIRECTORY_SEPARATOR . 'stubs');
    }
    /**
     * @return string
     */
    public function frameworkPath() {
        return realpath(__DIR__ . '/../../../../framework');
    }
    /**
     * @param mixed
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
     * @return bool
     */
    public function isLocal() {
        return $this['env'] == 'local';
    }
    /**
     * @return bool
     */
    public function runningInConsole() {
        return php_sapi_name() == 'cli';
    }
    /**
     * @return bool
     */
    public function runningUnitTests() {
        return $this['env'] == 'testing';
    }
    /**
     * @return void
     */
    public function registerConfiguredProviders() {
        $this->register(ConsoleServiceProvider::class);
        $this->register(ConsoleSupportServiceProvider::class);
        $this->register(AuthServiceProvider::class);
        $this->register(PasswordResetServiceProvider::class);
        $this->register(BroadcastServiceProvider::class);
        $this->register(BusServiceProvider::class);
        $this->register(CacheServiceProvider::class);
        $this->register(ControllerServiceProvider::class);
        $this->register(CookieServiceProvider::class);
        $this->register(DatabaseServiceProvider::class);
        $this->register(EncryptionServiceProvider::class);
        $this->register(ScheduleServiceProvider::class);
        $this->register(GeneratorServiceProvider::class);
        $this->register(FilesystemServiceProvider::class);
        $this->register(HashServiceProvider::class);
        $this->register(MailServiceProvider::class);
        $this->register(PaginationServiceProvider::class);
        $this->register(PipelineServiceProvider::class);
        $this->register(QueueServiceProvider::class);
        $this->register(RedisServiceProvider::class);
        $this->register(SessionServiceProvider::class);
        $this->register(TranslationServiceProvider::class);
        $this->register(ValidationServiceProvider::class);
        $this->register(ViewServiceProvider::class);
        $this->register(ComposerServiceProvider::class);
        $this->register(FormRequestServiceProvider::class);
        $this->register(ExtensionServiceProvider::class);
        $this->register(SettingServiceProvider::class);
        $this->register(SearchEngineServiceProvider::class);
    }
    /**
     * @param \Illuminate\Support\ServiceProvider|string $provider
     * @param array $options
     * @param bool $force
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
     * @param \Illuminate\Support\ServiceProvider|string $provider
     * @return \Illuminate\Support\ServiceProvider|null
     */
    public function getProvider($provider) {
        $name = is_string($provider) ? $provider : get_class($provider);
        return Arr::first($this->serviceProviders, function ($key, $value) use ($name) {
            return $value instanceof $name;
        });
    }
    /**
     * @param string $provider
     * @return \Illuminate\Support\ServiceProvider
     */
    public function resolveProviderClass($provider) {
        return new $provider($this);
    }
    /**
     * @param \Illuminate\Support\ServiceProvider $provider
     * @return void
     */
    protected function markAsRegistered($provider) {
        $this['events']->fire($class = get_class($provider), [$provider]);
        $this->serviceProviders[] = $provider;
        $this->loadedProviders[$class] = true;
    }
    /**
     * @return void
     */
    public function loadDeferredProviders() {
        foreach($this->deferredServices as $service => $provider) {
            $this->loadDeferredProvider($service);
        }
        $this->deferredServices = [];
    }
    /**
     * @param string $service
     * @return void
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
     * @param string $provider
     * @param string $service
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
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make($abstract, array $parameters = []) {
        $abstract = $this->getAlias($abstract);
        if(isset($this->deferredServices[$abstract])) {
            $this->loadDeferredProvider($abstract);
        }
        return parent::make($abstract, $parameters);
    }
    /**
     * (Overriding Container::bound)
     * @param string $abstract
     * @return bool
     */
    public function bound($abstract) {
        return isset($this->deferredServices[$abstract]) || parent::bound($abstract);
    }
    /**
     * @return bool
     */
    public function isBooted() {
        return $this->booted;
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
     * @param \Illuminate\Support\ServiceProvider $provider
     * @return void
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
     * @param mixed $callback
     * @return void
     */
    public function booting($callback) {
        $this->bootingCallbacks[] = $callback;
    }
    /**
     * @param mixed $callback
     * @return void
     */
    public function booted($callback) {
        $this->bootedCallbacks[] = $callback;
        if($this->isBooted()) {
            $this->fireAppCallbacks([$callback]);
        }
    }
    /**
     * @param array $callbacks
     * @return void
     */
    protected function fireAppCallbacks(array $callbacks) {
        foreach($callbacks as $callback) {
            call_user_func($callback, $this);
        }
    }
    /**
     * @param SymfonyRequest $request
     * @param int $type
     * @param bool|true $catch
     * @return mixed
     */
    public function handle(SymfonyRequest $request, $type = self::MASTER_REQUEST, $catch = true) {
        return $this['Illuminate\Contracts\Http\Kernel']->handle(Request::createFromBase($request));
    }
    /**
     * @return bool
     */
    public function shouldSkipMiddleware() {
        return $this->bound('middleware.disable') && $this->make('middleware.disable') === true;
    }
    /**
     * @return bool
     */
    public function configurationIsCached() {
        return $this['files']->exists($this->getCachedConfigPath());
    }
    /**
     * @return string
     */
    public function getCachedConfigPath() {
        return $this->basePath() . '/bootstrap/cache/config.php';
    }
    /**
     * @return bool
     */
    public function routesAreCached() {
        return $this['files']->exists($this->getCachedRoutesPath());
    }
    /**
     * @return string
     */
    public function getCachedRoutesPath() {
        return $this->storagePath() . '/framework/cache/routes.php';
    }
    /**
     * @return string
     */
    public function getCachedCompilePath() {
        return $this->storagePath() . '/framework/cache/compiled.php';
    }
    /**
     * @return string
     */
    public function getCachedServicesPath() {
        return $this->storagePath() . '/framework/cache/services.json';
    }
    /**
     * @return bool
     */
    public function isDownForMaintenance() {
        return file_exists($this->storagePath() . '/notadd/down');
    }
    /**
     * @return bool
     */
    public function isInstalled() {
        return file_exists($this->storagePath() . '/notadd/installed');
    }
    /**
     * @return bool
     */
    public function inDebugMode() {
        return file_exists($this->storagePath() . '/notadd/debug');
    }
    /**
     * @param int $code
     * @param string $message
     * @param array $headers
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function abort($code, $message = '', array $headers = []) {
        if($code == 404) {
            throw new NotFoundHttpException($message);
        }
        throw new HttpException($code, $message, null, $headers);
    }
    /**
     * @param \Closure $callback
     * @return $this
     */
    public function terminating(Closure $callback) {
        $this->terminatingCallbacks[] = $callback;
        return $this;
    }
    /**
     * @return void
     */
    public function terminate() {
        foreach($this->terminatingCallbacks as $terminating) {
            $this->call($terminating);
        }
    }
    /**
     * @return array
     */
    public function getLoadedProviders() {
        return $this->loadedProviders;
    }
    /**
     * @return array
     */
    public function getDeferredServices() {
        return $this->deferredServices;
    }
    /**
     * @param array $services
     * @return void
     */
    public function setDeferredServices(array $services) {
        $this->deferredServices = $services;
    }
    /**
     * @param array $services
     * @return void
     */
    public function addDeferredServices(array $services) {
        $this->deferredServices = array_merge($this->deferredServices, $services);
    }
    /**
     * @param string $service
     * @return bool
     */
    public function isDeferredService($service) {
        return isset($this->deferredServices[$service]);
    }
    /**
     * @param callable $callback
     * @return $this
     */
    public function configureMonologUsing(callable $callback) {
        $this->monologConfigurator = $callback;
        return $this;
    }
    /**
     * @return bool
     */
    public function hasMonologConfigurator() {
        return !is_null($this->monologConfigurator);
    }
    /**
     * @return callable
     */
    public function getMonologConfigurator() {
        return $this->monologConfigurator;
    }
    /**
     * @return string
     */
    public function getLocale() {
        return $this['config']->get('app.locale');
    }
    /**
     * @param string $locale
     * @return void
     */
    public function setLocale($locale) {
        $this['config']->set('app.locale', $locale);
        $this['translator']->setLocale($locale);
        $this['events']->fire('locale.changed', [$locale]);
    }
    /**
     * @return void
     */
    public function registerCoreContainerAliases() {
        $aliases = [
            'app' => [
                'Illuminate\Foundation\Application',
                'Illuminate\Contracts\Container\Container',
                'Illuminate\Contracts\Foundation\Application'
            ],
            'auth' => 'Illuminate\Auth\AuthManager',
            'auth.driver' => [
                'Illuminate\Auth\Guard',
                'Illuminate\Contracts\Auth\Guard'
            ],
            'auth.password.tokens' => 'Illuminate\Auth\Passwords\TokenRepositoryInterface',
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
            'cookie' => [
                'Illuminate\Cookie\CookieJar',
                'Illuminate\Contracts\Cookie\Factory',
                'Illuminate\Contracts\Cookie\QueueingFactory'
            ],
            'encrypter' => [
                'Illuminate\Encryption\Encrypter',
                'Illuminate\Contracts\Encryption\Encrypter'
            ],
            'db' => 'Notadd\Foundation\Database\DatabaseManager',
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
            'translator' => [
                'Illuminate\Translation\Translator',
                'Symfony\Component\Translation\TranslatorInterface'
            ],
            'log' => [
                'Illuminate\Log\Writer',
                'Illuminate\Contracts\Logging\Log',
                'Psr\Log\LoggerInterface'
            ],
            'mailer' => [
                'Illuminate\Mail\Mailer',
                'Illuminate\Contracts\Mail\Mailer',
                'Illuminate\Contracts\Mail\MailQueue'
            ],
            'auth.password' => [
                'Illuminate\Auth\Passwords\PasswordBroker',
                'Illuminate\Contracts\Auth\PasswordBroker'
            ],
            'queue' => [
                'Illuminate\Queue\QueueManager',
                'Illuminate\Contracts\Queue\Factory',
                'Illuminate\Contracts\Queue\Monitor'
            ],
            'queue.connection' => 'Illuminate\Contracts\Queue\Queue',
            'redirect' => 'Illuminate\Routing\Redirector',
            'redis' => [
                'Illuminate\Redis\Database',
                'Illuminate\Contracts\Redis\Database'
            ],
            'request' => 'Illuminate\Http\Request',
            'router' => [
                'Illuminate\Routing\Router',
                'Illuminate\Contracts\Routing\Registrar'
            ],
            'session' => 'Illuminate\Session\SessionManager',
            'session.store' => [
                'Illuminate\Session\Store',
                'Symfony\Component\HttpFoundation\Session\SessionInterface'
            ],
            'url' => [
                'Illuminate\Routing\UrlGenerator',
                'Illuminate\Contracts\Routing\UrlGenerator'
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
     * @return void
     */
    public function flush() {
        parent::flush();
        $this->loadedProviders = [];
    }
    /**
     * @return \Illuminate\Contracts\Console\Kernel|\Illuminate\Contracts\Http\Kernel
     */
    protected function getKernel() {
        $kernelContract = $this->runningInConsole() ? 'Illuminate\Contracts\Console\Kernel' : 'Illuminate\Contracts\Http\Kernel';
        return $this->make($kernelContract);
    }
    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getNamespace() {
        if(!is_null($this->namespace)) {
            return $this->namespace;
        }
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        foreach((array)data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach((array)$path as $pathChoice) {
                if(realpath(app_path()) == realpath(base_path() . '/' . $pathChoice)) {
                    return $this->namespace = $namespace;
                }
            }
        }
        throw new RuntimeException('Unable to detect application namespace.');
    }
}