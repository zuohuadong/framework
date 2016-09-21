<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 17:38
 */
namespace Notadd\Upgrade\Listeners;
use Notadd\Foundation\Abstracts\AbstractEventSubscriber;
use Notadd\Foundation\Console\Events\CommandRegister as CommandRegisterEvent;
use Notadd\Upgrade\Commands\UpgradeCommand;
/**
 * Class CommandRegister
 * @package Notadd\Upgrade\Listeners
 */
class CommandRegister extends AbstractEventSubscriber {
    /**
     * @return string
     */
    protected function getEvent() {
        return CommandRegisterEvent::class;
    }
    /**
     * @param \Notadd\Foundation\Console\Events\CommandRegister $console
     */
    public function handle(CommandRegisterEvent $console) {
        $console->registerCommand($this->container->make(UpgradeCommand::class));
    }
}