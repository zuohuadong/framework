<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-18 16:28
 */
namespace Notadd\Foundation\Routing;
use BadMethodCallException;
use Illuminate\Container\Container;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Routing\Controller as IlluminateController;
use Illuminate\Support\Str;
use Notadd\Foundation\Auth\Access\AuthorizesRequests;
use Notadd\Foundation\Bus\DispatchesJobs;
use Notadd\Foundation\SearchEngine\Optimization;
use Notadd\Foundation\Validation\ValidatesRequests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/**
 * Class Controller
 * @package Notadd\Foundation\Routing
 */
abstract class Controller extends IlluminateController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * @var \Illuminate\Contracts\Logging\Log
     */
    protected $log;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    protected $redirect;
    /**
     * @var \Notadd\Setting\Factory
     */
    protected $setting;
    /**
     * @var \Notadd\Foundation\SearchEngine\Optimization
     */
    protected $seo;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;
    /**
     * Controller constructor.
     */
    public function __construct() {
        $this->app = Container::getInstance();
        $this->events = $this->app->make('events');
        $this->log = $this->app->make('log');
        $this->redirect = $this->app->make('redirect');
        $this->setting = $this->app->make('setting');
        $this->seo = $this->app->make(Optimization::class);
        $this->view = $this->app->make('view');
    }
    /**
     * @param string $command
     * @return \Illuminate\Console\Command
     */
    public function getCommand($command) {
        return $this->app->make(Kernel::class)->find($command);
    }
    /**
     * @param array $parameters
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function missingMethod($parameters = []) {
        throw new NotFoundHttpException('控制器方法未找到。');
    }
    /**
     * @param $key
     * @param null $value
     */
    protected function share($key, $value = null) {
        $this->view->share($key, $value);
    }
    /**
     * @param $template
     * @return \Illuminate\Contracts\View\View
     */
    protected function view($template) {
        if(Str::contains($template, '::')) {
            return $this->view->make($template);
        } else {
            return $this->view->make('themes::' . $template);
        }
    }
    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters) {
        throw new BadMethodCallException("方法[$method]不存在。");
    }
}