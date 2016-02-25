<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 16:12
 */
namespace Notadd\Foundation\Console;
/**
 * Class DownCommand
 * @package Notadd\Foundation\Console
 */
class DownCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'down';
    /**
     * @var string
     */
    protected $description = 'Put the application into maintenance mode';
    /**
     * @return void
     */
    public function fire() {
        touch($this->notadd->storagePath() . '/notadd/down');
        $this->comment('Application is now in maintenance mode.');
    }
}