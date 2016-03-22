<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 13:44
 */
namespace Notadd\Link;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Traits\InjectBladeTrait;
use Notadd\Foundation\Traits\InjectRouterTrait;
use Notadd\Foundation\Traits\InjectViewTrait;
use Notadd\Link\Controllers\Admin\LinkController as AdminLinkController;
use Notadd\Link\Controllers\LinkController;
use Notadd\Link\Models\Link as LinkModel;
use Notadd\Link\Observers\LinkObserver;
/**
 * Class LinkServiceProvider
 * @package Notadd\Link
 */
class LinkServiceProvider extends ServiceProvider {
    use InjectBladeTrait, InjectRouterTrait, InjectViewTrait;
    /**
     * @return void
     */
    public function boot() {
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->getRouter()->resource('link', AdminLinkController::class);
        });
        $this->getRouter()->resource('link', LinkController::class);
        $this->getView()->share('__link', $this->app->make('link'));
        $this->getBlade()->directive('link', function($expression) {
            $segments = explode(',', preg_replace("/[\(\)\\\"\']/", '', $expression));
            return "<?php \$__tmp = \$__link->handle(" . trim($segments[0]) . "); foreach(\$__tmp as \$" . trim($segments[1]) . "=>\$" . trim($segments[2]) . "): ?>";
        });
        $this->getBlade()->directive('endlink', function($expression) {
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