<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-01 13:42
 */
namespace Notadd\Flash;
use Illuminate\Support\ServiceProvider;
use Notadd\Flash\Controllers\Admin\GroupController;
use Notadd\Flash\Controllers\Admin\ItemController;
use Notadd\Flash\Models\FlashItem;
use Notadd\Flash\Observers\FlashItemObserver;
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
            $this->getRouter()->resource('flash', GroupController::class);
            $this->getRouter()->resource('flash/item', ItemController::class);
            $this->getRouter()->post('flash/item/{id}/status', ItemController::class . '@status');
        });
        FlashItem::observe(FlashItemObserver::class);
    }
    /**
     * @return void
     */
    public function register() {
    }
}