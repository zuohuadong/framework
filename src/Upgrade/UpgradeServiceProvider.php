<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 16:45
 */
namespace Notadd\Upgrade;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Notadd\Upgrade\Listeners\CommandRegister;
use Notadd\Upgrade\Listeners\RouteRegister;
/**
 * Class UpgradeServiceProvider
 * @package Notadd\Upgrade
 */
class UpgradeServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->events->subscribe(CommandRegister::class);
        $this->events->subscribe(RouteRegister::class);
    }
}