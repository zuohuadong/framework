<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 16:25
 */
namespace Notadd\Foundation\Console;
/**
 * Class UpCommand
 * @package Notadd\Foundation\Console
 */
class UpCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'up';
    /**
     * @var string
     */
    protected $description = 'Bring the application out of maintenance mode';
    /**
     * @return void
     */
    public function fire() {
        @unlink($this->notadd->storagePath() . '/notadd/down');
        $this->info('Application is now live.');
    }
}