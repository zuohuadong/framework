<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:25
 */
namespace Notadd\Foundation\Image;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Image\Caches\CachedImage;
use Notadd\Foundation\Image\Caches\ImageCache;
use Notadd\Foundation\Image\Console\ImageCacheClearCommand;
use Notadd\Foundation\Image\Contracts\BinLocator;
use Notadd\Foundation\Image\Contracts\Cache;
use Notadd\Foundation\Image\Contracts\Driver;
use Notadd\Foundation\Image\Contracts\FileResponse;
use Notadd\Foundation\Image\Contracts\Image as ImageContract;
use Notadd\Foundation\Image\Contracts\Resolver;
use Notadd\Foundation\Image\Contracts\ResolverConfiguration;
use Notadd\Foundation\Image\Contracts\SourceLoader;
use Notadd\Foundation\Image\Drivers\ImageSourceLoader;
use Notadd\Foundation\Image\Drivers\ImBinLocator;
use Notadd\Foundation\Image\Proxies\ProxyImage;
use Notadd\Foundation\Traits\InjectConfigTrait;
/**
 * Class ImageServiceProvider
 * @package Notadd\Foundation\Image
 */
class ImageServiceProvider extends ServiceProvider {
    use InjectConfigTrait;
    /**
     * @var bool
     */
    protected $deferred = true;
    /**
     * @return void
     */
    public function boot() {
        $this->registerResponse();
        $this->registerController();
        $this->registerCommands();
    }
    /**
     * @return void
     */
    public function register() {
        $this->registerDriver();
        $this->registerResolver();
    }
    /**
     * @return array
     */
    public function provides() {
        return [
            'image',
            'image.cache'
        ];
    }
    /**
     * @return void
     */
    protected function registerDriver() {
        $storage = $this->getConfig()->get('image.cache.path');
        $driver = sprintf('\Notadd\Image\Driver\%sDriver', $driverName = ucfirst($this->getConfig()->get('image.driver', 'gd')));
        $this->app->bind(Cache::class, function () use ($storage) {
            $cache = new ImageCache(new CachedImage, new Filesystem, $storage . '/image');
            return $cache;
        });
        $this->app->bind(SourceLoader::class, ImageSourceLoader::class);
        $this->app->bind(BinLocator::class, function () {
            $locator = new ImBinLocator;
            extract($this->getConfig()->get('image.imagemagick', [
                'path' => '/usr/local/bin',
                'bin' => 'convert'
            ]));
            $locator->setConverterPath(sprintf('%s%s%s', rtrim($path, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR, $bin));
            return $locator;
        });
        $this->app->bind(Driver::class, function () use ($driver) {
            return $this->app->make($driver);
        });
        $this->app->bind(ImageContract::class, function () {
            return new ProxyImage(function () {
                $image = new Image($this->app->make(Driver::class));
                $image->setQuality($this->getConfig()->get('image.quality', 80));
                return $image;
            });
        });
        $this->app['image'] = $this->app->share(function () {
            $resolver = $this->app->make(Resolver::class);
            $image = new ImageAdapter($resolver, $this->app->make('url')->to('/'));
            return $image;
        });
        $this->app['image.cache'] = $this->app->share(function () {
            return $this->app->make(Cache::class);
        });
        $this->registerFilter($driverName, $this->getFilters());
    }
    /**
     * @return void
     */
    protected function registerResolver() {
        $this->app->singleton(Resolver::class, ImageResolver::class);
        $this->app->bind(ResolverConfiguration::class, function () {
            $conf = [
                'trusted_sites' => $this->getConfig()->get('image.trusted-sites', []),
                'cache_prefix' => $this->getConfig()->get('image.cache.prefix', 'notadd_'),
                'base_route' => $this->getConfig()->get('image.route', 'images'),
                'cache_route' => $this->getConfig()->get('image.cache.route', 'jit/storage'),
                'base' => $this->getConfig()->get('image.base', public_path()),
                'cache' => in_array($this->app->environment(), $this->getConfig()->get('image.cache.environments', [])),
                'format_filter' => $this->getConfig()->get('image::filter.Convert', 'conv')
            ];
            return new ResolveConfiguration($conf);
        });
    }
    /**
     * @return void
     */
    protected function registerResponse() {
        $type = $this->getConfig()->get('image.response-type', 'generic');
        $response = sprintf('Notadd\Image\Response\%sFileResponse', ucfirst($type));
        $this->app->bind(FileResponse::class, function () use ($response) {
            return new $response($this->app['request']);
        });
    }
    /**
     * @return void;
     */
    protected function registerController() {
        $recipes = $this->getConfig()->get('image.recipes', []);
        $route = $this->getConfig()->get('image.route', 'image');
        $cacheroute = $this->getConfig()->get('image.cache.route', 'notadd/storage');
        $this->registerCacheRoute($cacheroute);
        if(false === $this->registerStaticRoutes($recipes, $route)) {
            $this->registerDynanmicRoute($route);
        }
    }
    /**
     * @param string $route
     * @return void
     */
    protected function registerCacheRoute($route) {
        $this->app['router']->get($route . '/{id}', 'Notadd\Image\Controller\JitController@getCached')->where('id', '(.*\/){1}.*');
    }
    /**
     * @param  array $recipes
     * @param  string $route
     * @return void|boolean false
     */
    protected function registerStaticRoutes(array $recipes = [], $route = null) {
        if(empty($recipes)) {
            return false;
        }
        $ctrl = 'Notadd\Image\Controller\Controller';
        foreach($recipes as $aliasRoute => $formular) {
            $param = str_replace('/', '_', $aliasRoute);
            $this->app['router']->get($route . '/{' . $param . '}/{source}', ['uses' => $ctrl . '@getResource'])->where($param, $aliasRoute)->where('source', '(.*)');
        }
        $this->app->bind($ctrl, function () use ($ctrl, $recipes) {
            $controller = new $ctrl($this->app->make(Resolver::class), $this->app->make(FileResponse::class));
            $controller->setRecieps(new RecipeResolver($recipes));
            return $controller;
        });
    }
    /**
     * @param string $route
     * @return void
     */
    protected function registerDynanmicRoute($route) {
        $this->app['router']->get($route . '/{params}/{source}/{filter?}', 'Thapp\JitImage\Controller\JitController@getImage')// matching different modes:
            ->where('params', '([5|6](\/\d+){1}|[0]|[1|4](\/\d+){2}|[2](\/\d+){3}|[3](\/\d+){3}\/?([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})?)')// match the image source:
            ->where('source', '((([^0-9A-Fa-f]{3}|[^0-9A-Fa-f]{6})?).*?.(?=(\/filter:.*)?))')// match the filter:
            ->where('filter', '(filter:.*)');
    }
    /**
     * @return void
     */
    protected function registerCommands() {
        $this->app['command.image.clearcache'] = $this->app->share(function ($app) {
            return new ImageCacheClearCommand($app['image.cache']);
        });
        $this->commands('command.image.clearcache');
    }
    /**
     * @param mixed $driverName
     * @param $filters
     */
    protected function registerFilter($driverName, $filters) {
        $this->app->extend(Driver::class, function ($driver) use ($driverName, $filters) {
            $addFilters = $this->app['events']->fire('image.registerfilter', [$driverName]);
            foreach($addFilters as $filter) {
                foreach((array)$filter as $name => $class) {
                    if(class_exists($class)) {
                        $driver->registerFilter($name, $class);
                    } else {
                        throw new \InvalidArgumentException(sprintf('Filterclass %s for %s driver does not exists', $class, $driverName));
                    }
                }
            }
            foreach($filters as $name => $filter) {
                $driver->registerFilter($filter, sprintf('Notadd\Image\Filter\%s\%s%sFilter', $name, $driverName, ucfirst($filter)));
            }
            return $driver;
        });
    }
    /**
     * @return array
     */
    private function getFilters() {
        return $this->app['config']->get('image.filter', []);
    }
}