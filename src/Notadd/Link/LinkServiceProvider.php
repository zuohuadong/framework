<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 13:44
 */
namespace Notadd\Link;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Notadd\Link\Controllers\Admin\LinkController as AdminLinkController;
use Notadd\Link\Controllers\LinkController;
use Notadd\Link\Models\Link as LinkModel;
use Notadd\Link\Observers\LinkObserver;
/**
 * Class LinkServiceProvider
 * @package Notadd\Link
 */
class LinkServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->router->resource('link', AdminLinkController::class);
        });
        $this->router->resource('link', LinkController::class);
        $this->view->share('__link', $this->app->make('link'));
        $this->blade->directive('link', function($expression) {
            $segments = explode(',', preg_replace("/[\(\)\\\"\']/", '', $expression));
            return "<?php \$__tmp = \$__link->handle(" . trim($segments[0]) . "); foreach(\$__tmp as \$" . trim($segments[1]) . "=>\$" . trim($segments[2]) . "): ?>";
        });
        $this->blade->directive('endlink', function($expression) {
            return "<?php endforeach; ?>";
        });
        LinkModel::observe(LinkObserver::class);
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('link', Factory::class);
    }
}