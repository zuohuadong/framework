<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-26 16:04
 */
namespace Notadd\Foundation\Http\Abstracts;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Notadd\Foundation\Http\Contracts\ControllerContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
/**
 * Class AbstractController
 * @package Notadd\Foundation\Http\Abstracts
 */
abstract class AbstractController implements ControllerContract {
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $application;
    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;
    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $db;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * @var \Illuminate\Mail\Mailer
     */
    protected $mailer;
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;
    /**
     * AbstractController constructor.
     */
    public function __construct() {
        $this->application = Container::getInstance();
        $this->config = $this->application->make('config');
        $this->db = $this->application->make('db');
        $this->events = $this->application->make('events');
        $this->mailer = $this->application->make('mailer');
        $this->request = $this->application->make(ServerRequestInterface::class);
        $this->response = $this->application->make(ResponseInterface::class);
        $this->view = $this->application->make('view');
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