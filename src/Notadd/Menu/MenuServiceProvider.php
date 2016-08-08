<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 15:02
 */
namespace Notadd\Menu;
use Illuminate\Routing\Events\RouteMatched;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Notadd\Menu\Controllers\Admin\GroupController;
use Notadd\Menu\Controllers\Admin\ItemController;
use Notadd\Menu\Models\Menu;
use Notadd\Menu\Observers\MenuItemObserver;
/**
 * Class MenuServiceProvider
 * @package Notadd\Menu
 */
class MenuServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->router->resource('menu', GroupController::class);
            $this->router->get('menu/{id}/sort', GroupController::class . '@sort');
            $this->router->post('menu/{id}/sorting', GroupController::class . '@sorting');
            $this->router->resource('menu/item', ItemController::class);
            $this->router->post('menu/item/{id}/status', ItemController::class . '@status');
            $this->router->get('menu/item/{id}/sort', ItemController::class . '@sort');
            $this->router->post('menu/item/{id}/sorting', ItemController::class . '@sorting');
        });
        $this->events->listen(RouteMatched::class, function () {
            $this->view->share('__menu', $this->app->make('menu'));
        });
        $this->blade->directive('menu', function($expression) {
            $segments = explode(',', preg_replace("/[\(\)\\\"\']/", '', $expression));
            return "<?php \$__tmp = \$__menu->make(" . trim($segments[0]) . "); foreach(\$__tmp as \$" . trim($segments[1]) . "=>\$" . trim($segments[2]) . "): ?>";
        });
        $this->blade->directive('endmenu', function($expression) {
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