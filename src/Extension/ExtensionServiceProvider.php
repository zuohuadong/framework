<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-29 14:06
 */
namespace Notadd\Extension;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
/**
 * Class ExtensionServiceProvider
 * @package Notadd\Extension
 */
class ExtensionServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('extensions', ExtensionManager::class);
    }
}