<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 13:03
 */
namespace Notadd\Foundation\Abstracts;
use Illuminate\Support\ServiceProvider;
/**
 * Class AbstractServiceProvider
 * @package Notadd\Foundation\Abstracts
 */
abstract class AbstractServiceProvider extends ServiceProvider {
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $app;
    /**
     * @var \Illuminate\View\Compilers\BladeCompiler
     */
    protected $blade;
    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * @var \Notadd\Foundation\Routing\Router
     */
    protected $router;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;
    /**
     * AbstractServiceProvider constructor.
     * @param \Illuminate\Contracts\Foundation\Application $application
     */
    public function __construct($application) {
        parent::__construct($application);
        $this->config = $this->app->make('config');
        $this->events = $this->app->make('events');
        $this->router = $this->app->make('router');
        $this->view = $this->app->make('view');
        $this->blade = $this->view->getEngineResolver()->resolve('blade')->getCompiler();
        $this->loadViewsFrom(__DIR__ . '/../../../views/admin', 'admin');
        $this->loadViewsFrom(__DIR__ . '/../../../views/install', 'install');
        $this->loadViewsFrom(__DIR__ . '/../../../views/theme', 'theme');
    }
    /**
     * @return void
     */
    public function boot() {
    }
    /**
     * @return void
     */
    public function register() {
    }
}