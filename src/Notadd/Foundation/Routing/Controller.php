<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-18 16:28
 */
namespace Notadd\Foundation\Routing;
use BadMethodCallException;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Controller as IlluminateController;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Notadd\Foundation\Auth\Access\AuthorizesRequests;
use Notadd\Foundation\Bus\DispatchesJobs;
use Notadd\Foundation\SearchEngine\Optimization;
use Notadd\Foundation\Validation\ValidatesRequests;
use Notadd\Setting\Factory as SettingFactory;
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
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Events\Dispatcher $events
     * @param \Illuminate\Contracts\Logging\Log $log
     * @param \Illuminate\Routing\Redirector $redirect
     * @param \Notadd\Setting\Factory $setting
     * @param \Notadd\Foundation\SearchEngine\Optimization $seo
     * @param \Illuminate\Contracts\View\Factory $view
     */
    public function __construct(Application $app, Dispatcher $events, Log $log, Redirector $redirect, SettingFactory $setting, Optimization $seo, ViewFactory $view) {
        $this->app = $app;
        $this->events = $events;
        $this->log = $log;
        $this->redirect = $redirect;
        $this->setting = $setting;
        $this->seo = $seo;
        $this->view = $view;
    }
    /**
     * @param string $command
     * @return \Notadd\Foundation\Console\Command
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