<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-17 20:48
 */
namespace Notadd\Foundation\Extension;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
abstract class Extension extends ServiceProvider {
    /**
     * @return void
     */
    public function register() {
    }
}