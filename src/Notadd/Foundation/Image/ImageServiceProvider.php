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
use Notadd\Foundation\Image\Contracts\Resolver;
use Notadd\Foundation\Image\Contracts\ResolverConfiguration;
use Notadd\Foundation\Image\Contracts\SourceLoader;
use Notadd\Foundation\Image\Drivers\ImageSourceLoader;
use Notadd\Foundation\Image\Drivers\ImBinLocator;
use Notadd\Foundation\Image\Proxies\ProxyImage;
/**
 * Class ImageServiceProvider
 * @package Notadd\Foundation\Image
 */
class ImageServiceProvider extends ServiceProvider {
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
    protected function registerDriver() {
        $app = $this->app;
        $config = $app['config'];
        $storage = $config->get('image::cache.path');
        $driver = sprintf('\Thapp\JitImage\Driver\%sDriver', $driverName = ucfirst($config->get('image::driver', 'gd')));
        $app->bind(Cache::class, function () use ($storage) {
            $cache = new ImageCache(new CachedImage, new Filesystem, $storage . '/jit');
            return $cache;
        });
        $app->bind(SourceLoader::class, ImageSourceLoader::class);
        $app->bind(BinLocator::class, function () use ($config) {
            $locator = new ImBinLocator;
            extract($config->get('image::imagemagick', [
                'path' => '/usr/local/bin',
                'bin' => 'convert'
            ]));
            $locator->setConverterPath(sprintf('%s%s%s', rtrim($path, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR, $bin));
            return $locator;
        });
        $this->app->bind(Driver::class, function () use ($driver) {
            return $this->app->make($driver);
        });
        $this->app->bind('Thapp\JitImage\ImageInterface', function () use ($app) {
            return new ProxyImage(function () use ($app) {
                $image = new Image($app->make(Driver::class));
                $image->setQuality($app['config']->get('image::quality', 80));
                return $image;
            });
        });
        $this->app['image'] = $this->app->share(function () use ($app) {
            $resolver = $app->make(Resolver::class);
            $image = new ImageAdapter($resolver, $this->app->make('url')->to('/'));
            return $image;
        });
        $this->app['image.cache'] = $this->app->share(function () {
            return $this->app->make('Thapp\JitImage\Cache\CacheInterface');
        });
        $this->registerFilter($driverName, $this->getFilters());
    }
    /**
     * @access protected
     * @return void
     */
    protected function registerResolver() {
        $config = $this->app['config'];
        $this->app->singleton(Resolver::class, ImageResolver::class);
        $this->app->bind(ResolverConfiguration::class, function () use ($config) {
            $conf = [
                'trusted_sites' => $config->get('jitimage::trusted-sites', []),
                'cache_prefix' => $config->get('jitimage::cache.prefix', 'jit_'),
                'base_route' => $config->get('jitimage::route', 'images'),
                'cache_route' => $config->get('jitimage::cache.route', 'jit/storage'),
                'base' => $config->get('jitimage::base', public_path()),
                'cache' => in_array($config->getEnvironment(), $config->get('jitimage::cache.environments', [])),
                'format_filter' => $config->get('jitimage::filter.Convert', 'conv')
            ];
            return new ResolveConfiguration($conf);
        });
    }
    /**
     * @access protected
     * @return void
     */
    protected function registerResponse() {
        $app = $this->app;
        $type = $this->app['config']->get('image::response-type', 'generic');
        $response = sprintf('Thapp\JitImage\Response\%sFileResponse', ucfirst($type));
        $this->app->bind(FileResponse::class, function () use ($response, $app) {
            return new $response($app['request']);
        });
    }
    /**
     * @access protected
     * @return void;
     */
    protected function registerController() {
        $config = $this->app['config'];
        $recipes = $config->get('image::recipes', []);
        $route = $config->get('image::route', 'image');
        $cacheroute = $config->get('image::cache.route', 'jit/storage');
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
        $this->app['router']->get($route . '/{id}', 'Thapp\JitImage\Controller\JitController@getCached')->where('id', '(.*\/){1}.*');
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
        $ctrl = 'Thapp\JitImage\Controller\JitController';
        foreach($recipes as $aliasRoute => $formular) {
            $param = str_replace('/', '_', $aliasRoute);
            $this->app['router']->get($route . '/{' . $param . '}/{source}', ['uses' => $ctrl . '@getResource'])->where($param, $aliasRoute)->where('source', '(.*)');
        }
        $this->app->bind($ctrl, function () use ($ctrl, $recipes) {
            $controller = new $ctrl($this->app->make('Thapp\JitImage\ResolverInterface'), $this->app->make('Thapp\JitImage\Response\FileResponseInterface'));
            $controller->setRecieps(new RecipeResolver($recipes));
            return $controller;
        });
    }
    /**
     * @param  string $route
     * @access protected
     * @return void
     */
    protected function registerDynanmicRoute($route) {
        $this->app['router']->get($route . '/{params}/{source}/{filter?}', 'Thapp\JitImage\Controller\JitController@getImage')// matching different modes:
            ->where('params', '([5|6](\/\d+){1}|[0]|[1|4](\/\d+){2}|[2](\/\d+){3}|[3](\/\d+){3}\/?([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})?)')// match the image source:
            ->where('source', '((([^0-9A-Fa-f]{3}|[^0-9A-Fa-f]{6})?).*?.(?=(\/filter:.*)?))')// match the filter:
            ->where('filter', '(filter:.*)');
    }
    /**
     * @access protected
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
     * @access protected
     * @return void
     */
    protected function registerFilter($driverName, $filters) {
        $this->app->extend('Thapp\JitImage\Driver\DriverInterface', function ($driver) use ($driverName, $filters) {
            $addFilters = $this->app['events']->fire('jitimage.registerfilter', [$driverName]);
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
                $driver->registerFilter($filter, sprintf('Thapp\JitImage\Filter\%s\%s%sFilter', $name, $driverName, ucfirst($filter)));
            }
            return $driver;
        });
    }
    /**
     * @access private
     * @return array
     */
    private function getFilters() {
        return $this->app['config']->get('image::filter', []);
    }
}