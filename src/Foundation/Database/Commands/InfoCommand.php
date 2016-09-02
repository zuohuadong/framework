<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-03 02:29
 */
namespace Notadd\Foundation\Database\Commands;
use Notadd\Foundation\Console\Abstracts\AbstractCommand;
/**
 * Class InfoCommand
 * @package Notadd\Foundation\Database\Commands
 */
class InfoCommand extends AbstractCommand {
    /**
     * @return void
     */
    protected function configure() {
        $this->setName('db')->setDescription('查看数据库链接信息');
    }
    /**
     * @return void
     */
    protected function fire() {
        $this->info('测试数据成功！');
    }
}