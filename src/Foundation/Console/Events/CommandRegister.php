<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-03 03:01
 */
namespace Notadd\Foundation\Console\Events;
use Notadd\Foundation\Application as Container;
use Notadd\Foundation\Console\Abstracts\AbstractCommand;
use Notadd\Foundation\Console\Application as Console;
/**
 * Class CommandRegister
 * @package Notadd\Foundation\Console\Events
 */
class CommandRegister {
    /**
     * @var \Notadd\Foundation\Console\Application
     */
    protected $console;
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $container;
    /**
     * CommandRegister constructor.
     * @param \Notadd\Foundation\Application $container
     * @param \Notadd\Foundation\Console\Application $console
     */
    public function __construct(Container $container, Console $console) {
        $this->console = $console;
        $this->container = $container;
    }
    /**
     * @param \Notadd\Foundation\Console\Abstracts\AbstractCommand $command
     * @return null|\Symfony\Component\Console\Command\Command
     */
    public function registerCommand(AbstractCommand $command) {
        return $this->console->add($command);
    }
}