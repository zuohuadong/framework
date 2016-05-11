<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 15:02
 */
namespace Notadd\Menu;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Traits\InjectBladeTrait;
use Notadd\Foundation\Traits\InjectEventsTrait;
use Notadd\Foundation\Traits\InjectRouterTrait;
use Notadd\Foundation\Traits\InjectViewTrait;
use Notadd\Menu\Controllers\Admin\GroupController;
use Notadd\Menu\Controllers\Admin\ItemController;
use Notadd\Menu\Models\Menu;
use Notadd\Menu\Observers\MenuItemObserver;
/**
 * Class MenuServiceProvider
 * @package Notadd\Menu
 */
class MenuServiceProvider extends ServiceProvider {
    use InjectBladeTrait, InjectEventsTrait, InjectRouterTrait, InjectViewTrait;
    /**
     * @return void
     */
    public function boot() {
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->getRouter()->resource('menu', GroupController::class);
            $this->getRouter()->get('menu/{id}/sort', GroupController::class . '@sort');
            $this->getRouter()->post('menu/{id}/sorting', GroupController::class . '@sorting');
            $this->getRouter()->resource('menu/item', ItemController::class);
            $this->getRouter()->post('menu/item/{id}/status', ItemController::class . '@status');
            $this->getRouter()->get('menu/item/{id}/sort', ItemController::class . '@sort');
            $this->getRouter()->post('menu/item/{id}/sorting', ItemController::class . '@sorting');
        });
        $this->getEvents()->listen(RouteMatched::class, function () {
            $this->getView()->share('__menu', $this->app->make('menu'));
        });
        $this->getBlade()->directive('menu', function($expression) {
            $segments = explode(',', preg_replace("/[\(\)\\\"\']/", '', $expression));
            return "<?php \$__tmp = \$__menu->make(" . trim($segments[0]) . "); foreach(\$__tmp as \$" . trim($segments[1]) . "=>\$" . trim($segments[2]) . "): ?>";
        });
        $this->getBlade()->directive('endmenu', function($expression) {
            return "<?php endforeach; ?>";
        });
        Menu::observe(MenuItemObserver::class);
    }
    /**
     * @return array
     */
    public function provides() {
        return ['menu'];
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('menu', function ($app) {
            return $this->app->make(Factory::class);
        });
    }
}