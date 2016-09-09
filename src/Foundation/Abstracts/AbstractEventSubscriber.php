<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 18:07
 */
namespace Notadd\Foundation\Abstracts;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Str;
use Notadd\Foundation\Application;
/**
 * Class AbstractEventSubscriber
 * @package Notadd\Foundation\Abstracts
 */
abstract class AbstractEventSubscriber {
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $application;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * RouteRegister constructor.
     * @param \Notadd\Foundation\Application $application
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function __construct(Application $application, Dispatcher $events) {
        $this->application = $application;
        $this->events = $events;
    }
    /**
     * @return string|object
     * @throws \Exception
     */
    protected function getEvent() {
        throw new \Exception('Event not found!', 404);
    }
    /**
     * @return void
     */
    public function subscribe() {
        $method = 'handle';
        if(method_exists($this, $getHandler = 'get' . Str::ucfirst($method) . 'r')) {
            $method = $this->{$getHandler}();
        }
        $this->events->listen($this->getEvent(), [$this, $method]);
    }
}