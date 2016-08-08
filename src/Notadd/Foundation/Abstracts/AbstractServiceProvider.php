<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-08 15:44
 */
namespace Notadd\Foundation\Abstracts;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
/**
 * Class AbstractServiceProvider
 * @package Notadd\Foundation\Abstracts
 */
abstract class AbstractServiceProvider extends ServiceProvider {
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
     * @var \Illuminate\Http\Request
     */
    protected $request;
    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;
    /**
     * @var \Notadd\Setting\Factory
     */
    protected $setting;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;
    /**
     * AbstractServiceProvider constructor.
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct($app) {
        parent::__construct($app);
        $this->config = Container::getInstance()->make('config');
        $this->events = Container::getInstance()->make('events');
        $this->request = Container::getInstance()->make('request');
        $this->router = Container::getInstance()->make('router');
        $this->setting = Container::getInstance()->make('setting');
        $this->view = Container::getInstance()->make('view');
        $this->blade = Container::getInstance()->make('view')->getEngineResolver()->resolve('blade')->getCompiler();
    }
    /**
     * @return void
     */
    public function register() {
    }
}