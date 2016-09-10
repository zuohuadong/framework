<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 17:35
 */
namespace Notadd\Upgrade\Commands;
use Notadd\Foundation\Console\Abstracts\AbstractCommand;
/**
 * Class UpgradeCommand
 * @package Notadd\Upgrade\Commands
 */
class UpgradeCommand extends AbstractCommand {
    /**
     * @return void
     */
    protected function configure() {
        $this->setDescription('Run upgrade program.');
        $this->setName('upgrade');
    }
    /**
     * @return void
     */
    protected function fire() {
    }
}