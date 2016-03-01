<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-01 13:42
 */
namespace Notadd\Flash;
use Illuminate\Support\ServiceProvider;
use Notadd\Flash\Controllers\Admin\FlashController;
use Notadd\Foundation\Traits\InjectRouterTrait;
/**
 * Class FlashServiceProvider
 * @package Notadd\Flash
 */
class FlashServiceProvider extends ServiceProvider {
    use InjectRouterTrait;
    /**
     * @return void
     */
    public function boot() {
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function() {
            $this->getRouter()->resource('flash', FlashController::class);
        });
    }
    /**
     * @return void
     */
    public function register() {
    }
}