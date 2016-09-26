<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-26 17:54
 */
namespace Notadd\Passport\Commands;
use Notadd\Foundation\Console\Abstracts\AbstractCommand;
use Symfony\Component\Console\Input\InputOption;
/**
 * Class InstallCommand
 * @package Notadd\Passport\Commands
 */
class InstallCommand extends AbstractCommand {
    /**
     * @return void
     */
    public function configure() {
        $this->setDescription('Run the commands necessary to prepare Passport for use.');
        $this->setName('passport:install');
    }
    /**
     * @return void
     */
    public function fire() {
        $this->call('passport:keys');
        $this->call('passport:client', ['--personal' => true, '--name' => config('app.name').' Personal Access Client']);
        $this->call('passport:client', ['--password' => true, '--name' => config('app.name').' Password Grant Client']);
    }
}