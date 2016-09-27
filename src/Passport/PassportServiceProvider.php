<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-26 17:52
 */
namespace Notadd\Passport;
use Notadd\Foundation\Passport\PassportServiceProvider as BaseServiceProvider;
use Notadd\Passport\Listeners\CommandRegister;
/**
 * Class PassportServiceProvider
 * @package Notadd\Passport
 */
class PassportServiceProvider extends BaseServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->app->make('events')->subscribe(CommandRegister::class);
    }
}