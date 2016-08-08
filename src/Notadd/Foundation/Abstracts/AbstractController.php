<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-18 16:28
 */
namespace Notadd\Foundation\Abstracts;
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
abstract class AbstractController extends IlluminateController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * @var \Notadd\Foundation\Agent\Agent
     */
    protected $agent;
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;
    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;
    /**
     * @var \Illuminate\Cookie\CookieJar
     */
    protected $cookie;
    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $db;
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
     * @var \Illuminate\Http\Request
     */
    protected $request;
    /**
     * @var \Illuminate\Session\Store
     */
    protected $session;
    /**
     * @var \Notadd\Setting\Factory
     */
    protected $setting;
    /**
     * @var \Notadd\Foundation\SearchEngine\Optimization
     */
    protected $seo;
    /**
     * @var \Notadd\Foundation\Auth\Models\User
     */
    protected $user;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;
    /**
     * Controller constructor.
     */
    public function __construct() {
        $this->agent = Container::getInstance()->make('agent');
        $this->app = Container::getInstance();
        $this->auth = Container::getInstance()->make('auth');
        $this->config = Container::getInstance()->make('config');
        $this->cookie = Container::getInstance()->make('cookie');
        $this->db = Container::getInstance()->make('db');
        $this->events = Container::getInstance()->make('events');
        $this->log = Container::getInstance()->make('log');
        $this->redirect = Container::getInstance()->make('redirect');
        $this->request = Container::getInstance()->make('request');
        $this->session = Container::getInstance()->make('session');
        $this->setting = Container::getInstance()->make('setting');
        $this->seo = Container::getInstance()->make(Optimization::class);
        $this->user = $this->auth->user();
        $this->view = Container::getInstance()->make('view');
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