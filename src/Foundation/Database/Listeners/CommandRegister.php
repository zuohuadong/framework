<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-03 03:09
 */
namespace Notadd\Foundation\Database\Listeners;
use Illuminate\Events\Dispatcher;
use Notadd\Foundation\Application;
use Notadd\Foundation\Console\Events\CommandRegister as CommandRegisterEvent;
use Notadd\Foundation\Database\Commands\InfoCommand;
use Notadd\Foundation\Database\Commands\InstallCommand;
use Notadd\Foundation\Database\Commands\MakeMigrationCommand;
use Notadd\Foundation\Database\Commands\MigrateCommand;
/**
 * Class CommandRegister
 * @package Notadd\Foundation\Database\Listeners
 */
class CommandRegister {
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
     * @param \Notadd\Foundation\Console\Events\CommandRegister $console
     */
    public function handle(CommandRegisterEvent $console) {
        $console->registerCommand(new InfoCommand());
        $console->registerCommand($this->application->make(InstallCommand::class));
        $console->registerCommand($this->application->make(MakeMigrationCommand::class));
        $console->registerCommand($this->application->make(MigrateCommand::class));
    }
    /**
     * @return void
     */
    public function subscribe() {
        $this->events->listen(CommandRegisterEvent::class, [$this, 'handle']);
    }
}