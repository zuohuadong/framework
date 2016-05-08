<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 16:14
 */
namespace Notadd\Foundation\Console;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\RouteCollection;
/**
 * Class RouteCacheCommand
 * @package Notadd\Foundation\Console
 */
class RouteCacheCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'route:cache';
    /**
     * @var string
     */
    protected $description = 'Create a route cache file for faster route registration';
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * RouteCacheCommand constructor.
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Filesystem $files) {
        parent::__construct();
        $this->files = $files;
    }
    /**
     * @return void
     */
    public function fire() {
        $this->call('route:clear');
        $routes = $this->getFreshApplicationRoutes();
        if(count($routes) == 0) {
            return $this->error("Your application doesn't have any routes.");
        }
        foreach($routes as $route) {
            $route->prepareForSerialization();
        }
        $this->files->put($this->laravel->getCachedRoutesPath(), $this->buildRouteCacheFile($routes));
        $this->info('Routes cached successfully!');
    }
    /**
     * @return mixed
     */
    protected function getFreshApplicationRoutes() {
        $app = require $this->laravel->basePath() . '/bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app['router']->getRoutes();
    }
    /**
     * @param \Illuminate\Routing\RouteCollection $routes
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildRouteCacheFile(RouteCollection $routes) {
        $stub = $this->files->get(__DIR__ . '/stubs/routes.stub');
        return str_replace('{{routes}}', base64_encode(serialize($routes)), $stub);
    }
}