<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-26 16:04
 */
namespace Notadd\Foundation\Routing\Abstracts;
use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Str;
use Notadd\Foundation\Console\Application;
use Notadd\Foundation\Routing\Contracts\Controller as ControllerContract;
use Notadd\Foundation\Routing\Responses\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
/**
 * Class AbstractController
 * @package Notadd\Foundation\Http\Abstracts
 */
abstract class AbstractController implements ControllerContract {
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;
    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;
    /**
     * @var \Illuminate\Mail\Mailer
     */
    protected $mailer;
    /**
     * @var array
     */
    protected $middleware = [];
    /**
     * @var \Notadd\Foundation\Routing\Responses\RedirectResponse
     */
    protected $redirect;
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;
    /**
     * @var \Notadd\Foundation\Session\Contracts\Session
     */
    protected $session;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;
    /**
     * AbstractController constructor.
     */
    public function __construct() {
        $this->container = Container::getInstance();
        $this->config = $this->container->make('config');
        $this->events = $this->container->make('events');
        $this->log = $this->container->make('log');
        $this->mailer = $this->container->make('mailer');
        $this->redirect = $this->container->make(RedirectResponse::class);
        $this->request = $this->container->make(ServerRequestInterface::class);
        $this->response = $this->container->make(ResponseInterface::class);
        $this->session = $this->request->getAttribute('session');
        $this->view = $this->container->make('view');
    }
    /**
     * @return \Notadd\Foundation\Console\Application
     */
    public function getConsole() {
        return Application::getInstance($this->container);
    }
    /**
     * @param string $name
     * @return \Symfony\Component\Console\Command\Command
     */
    public function getCommand($name) {
        return $this->getConsole()->find($name);
    }
    /**
     * @param string $method
     * @return array
     */
    public function getMiddlewareForMethod($method) {
        $middleware = [];
        foreach($this->middleware as $name => $options) {
            if(isset($options['only']) && !in_array($method, (array)$options['only'])) {
                continue;
            }
            if(isset($options['except']) && in_array($method, (array)$options['except'])) {
                continue;
            }
            $middleware[] = $name;
        }
        return $middleware;
    }
    /**
     * @return \Illuminate\Contracts\Validation\Factory|mixed
     */
    protected function getValidationFactory() {
        return $this->container->make(ValidationFactory::class);
    }
    /**
     * @param string $middleware
     * @param array $options
     * @return void
     */
    public function middleware($middleware, array $options = []) {
        $this->middleware[$middleware] = $options;
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
            return $this->view->make('theme::' . $template);
        }
    }
}