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
use Notadd\Foundation\Traits\InjectBladeTrait;
use Notadd\Foundation\Traits\InjectRouterTrait;
use Notadd\Foundation\Traits\InjectViewTrait;
/**
 * Class FlashServiceProvider
 * @package Notadd\Flash
 */
class FlashServiceProvider extends ServiceProvider {
    use InjectBladeTrait, InjectRouterTrait, InjectViewTrait;
    /**
     * @return void
     */
    public function boot() {
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function() {
            $this->getRouter()->resource('flash', GroupController::class);
            $this->getRouter()->resource('flash/item', ItemController::class);
            $this->getRouter()->post('flash/item/{id}/status', ItemController::class . '@status');
        });
        $this->getView()->share('__flash', $this->app->make('flash'));
        $this->getBlade()->directive('flash', function($expression) {
            $segments = explode(',', preg_replace("/[\(\)\\\"\']/", '', $expression));
            return "<?php \$__tmp = \$__flash->handle(" . trim($segments[0]) . "); foreach(\$__tmp as \$" . trim($segments[1]) . "=>\$" . trim($segments[2]) . "): ?>";
        });
        $this->getBlade()->directive('endflash', function($expression) {
            return "<?php endforeach; ?>";
        });
        FlashItem::observe(FlashItemObserver::class);
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('flash', Factory::class);
    }
}