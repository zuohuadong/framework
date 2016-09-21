<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-03 03:09
 */
namespace Notadd\Foundation\Database\Listeners;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Notadd\Foundation\Console\Events\CommandRegister as CommandRegisterEvent;
use Notadd\Foundation\Database\Commands\InfoCommand;
use Notadd\Foundation\Database\Commands\InstallCommand;
use Notadd\Foundation\Database\Commands\MakeMigrationCommand;
use Notadd\Foundation\Database\Commands\MigrateCommand;
use Notadd\Foundation\Database\Commands\RollbackCommand;
/**
 * Class CommandRegister
 * @package Notadd\Foundation\Database\Listeners
 */
class CommandRegister {
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
    public function handle(CommandRegisterEvent $console) {
        $console->registerCommand(new InfoCommand());
        $console->registerCommand($this->container->make(InstallCommand::class));
        $console->registerCommand($this->container->make(MakeMigrationCommand::class));
        $console->registerCommand($this->container->make(MigrateCommand::class));
        $console->registerCommand($this->container->make(RollbackCommand::class));
    }
    /**
     * @return void
     */
    public function subscribe() {
        $this->events->listen(CommandRegisterEvent::class, [$this, 'handle']);
    }
}