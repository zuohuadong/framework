<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-02 18:51
 */
namespace Notadd\Foundation\Console;
use Notadd\Foundation\Abstracts\AbstractServer;
use Notadd\Install\InstallServiceProvider;
/**
 * Class Server
 * @package Notadd\Foundation\Console
 */
class Server extends AbstractServer{
    /**
     * @return void
     */
    public function listen() {
        $console = $this->getConsoleApplication();
        exit($console->run());
    }
    /**
     * @return \Symfony\Component\Console\Application
     */
    protected function getConsoleApplication() {
        $app = $this->getApp();
        $app->register(InstallServiceProvider::class);
        $console = Application::getInstance($app, 'Notadd');
        return $console;
    }
}