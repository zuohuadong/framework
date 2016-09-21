<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 18:07
 */
namespace Notadd\Foundation\Abstracts;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Str;
/**
 * Class AbstractEventSubscriber
 * @package Notadd\Foundation\Abstracts
 */
abstract class AbstractEventSubscriber {
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * AbstractEventSubscriber constructor.
     * @param \Illuminate\Container\Container $container
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function __construct(Container $container, Dispatcher $events) {
        $this->container = $container;
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