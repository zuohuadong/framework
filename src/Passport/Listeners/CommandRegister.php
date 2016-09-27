<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-27 09:52
 */
namespace Notadd\Passport\Listeners;
use Notadd\Foundation\Console\Abstracts\AbstractCommandRegister;
use Notadd\Foundation\Console\Events\CommandRegister as CommandRegisterEvent;
use Notadd\Passport\Commands\ClientCommand;
use Notadd\Passport\Commands\InstallCommand;
use Notadd\Passport\Commands\KeysCommand;
/**
 * Class CommandRegister
 * @package Notadd\Passport\Listeners
 */
class CommandRegister extends AbstractCommandRegister {
    /**
     * @param \Notadd\Foundation\Console\Events\CommandRegister $console
     */
    public function handle(CommandRegisterEvent $console) {
        $console->registerCommand($this->container->make(ClientCommand::class));
        $console->registerCommand($this->container->make(InstallCommand::class));
        $console->registerCommand($this->container->make(KeysCommand::class));
    }
}