<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-27 09:49
 */
namespace Notadd\Foundation\Console\Abstracts;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Notadd\Foundation\Console\Events\CommandRegister;
/**
 * Class AbstractCommandRegister
 * @package Notadd\Foundation\Console\Abstracts
 */
abstract class AbstractCommandRegister {
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $container;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * CommandRegister constructor.
     * @param \Illuminate\Container\Container $container
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function __construct(Container $container, Dispatcher $events) {
        $this->container = $container;
        $this->events = $events;
    }
    /**
     * @param \Notadd\Foundation\Console\Events\CommandRegister $console
     */
    abstract public function handle(CommandRegister $console);
    /**
     * @return void
     */
    public function subscribe() {
        $this->events->listen(CommandRegister::class, [$this, 'handle']);
    }
}