<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-01 13:42
 */
namespace Notadd\Flash;
use Notadd\Flash\Controllers\Admin\GroupController;
use Notadd\Flash\Controllers\Admin\ItemController;
use Notadd\Flash\Models\FlashItem;
use Notadd\Flash\Observers\FlashItemObserver;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
/**
 * Class FlashServiceProvider
 * @package Notadd\Flash
 */
class FlashServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function() {
            $this->router->resource('flash', GroupController::class);
            $this->router->resource('flash/item', ItemController::class);
            $this->router->post('flash/item/{id}/status', ItemController::class . '@status');
        });
        $this->view->share('__flash', $this->app->make('flash'));
        $this->blade->directive('flash', function($expression) {
            $segments = explode(',', preg_replace("/[\(\)\\\"\']/", '', $expression));
            return "<?php \$__tmp = \$__flash->handle(" . trim($segments[0]) . "); foreach(\$__tmp as \$" . trim($segments[1]) . "=>\$" . trim($segments[2]) . "): ?>";
        });
        $this->blade->directive('endflash', function($expression) {
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