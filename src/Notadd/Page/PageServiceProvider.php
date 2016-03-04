<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 16:29
 */
namespace Notadd\Page;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Traits\InjectBladeTrait;
use Notadd\Foundation\Traits\InjectEventsTrait;
use Notadd\Foundation\Traits\InjectPageTrait;
use Notadd\Foundation\Traits\InjectRouterTrait;
use Notadd\Foundation\Traits\InjectSettingTrait;
use Notadd\Foundation\Traits\InjectViewTrait;
use Notadd\Page\Controllers\Admin\PageController as AdminPageController;
use Notadd\Page\Controllers\PageController;
use Notadd\Page\Models\Page as PageModel;
/**
 * Class PageServiceProvider
 * @package Notadd\Page
 */
class PageServiceProvider extends ServiceProvider {
    use InjectBladeTrait, InjectEventsTrait, InjectPageTrait, InjectRouterTrait, InjectSettingTrait, InjectViewTrait;
    /**
     * @return void
     */
    public function boot() {
        $pages = PageModel::whereEnabled(true)->get();
        foreach($pages as $value) {
            if($this->getSetting()->get('site.home') !== 'page_' . $value->id) {
                if($value->alias) {
                    $page = new Page($value->id);
                    $this->getRouter()->get($page->getRouting(), function() use ($page) {
                        return $this->app->call(PageController::class . '@show', ['id' => $page->getPageId()]);
                    });
                }
            }
        }
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->getRouter()->resource('page', AdminPageController::class);
            $this->getRouter()->post('page/{id}/delete', AdminPageController::class . '@delete');
            $this->getRouter()->get('page/{id}/move', AdminPageController::class . '@move');
            $this->getRouter()->post('page/{id}/moving', AdminPageController::class . '@moving');
            $this->getRouter()->post('page/{id}/restore', AdminPageController::class . '@restore');
            $this->getRouter()->get('page/{id}/sort', AdminPageController::class . '@sort');
            $this->getRouter()->post('page/{id}/sorting', AdminPageController::class . '@sorting');
        });
        $this->getRouter()->resource('page', PageController::class);
        $this->loadViewsFrom($this->app->basePath() . '/resources/views/pages/', 'page');
        $this->getEvents()->listen(RouteMatched::class, function () {
            $this->getView()->share('__call', $this->getPage());
        });
        $this->getBlade()->directive('call', function($expression) {
            return "<?php \$__tmp = \$__call->call{$expression}; foreach(\$__tmp as \$key=>\$value): ?>";
        });
        $this->getBlade()->directive('endcall', function($expression) {
            return "<?php endforeach; ?>";
        });
        $this->getBlade()->directive('flash', function($expression) {
            $segments = explode(',', preg_replace("/[\(\)\\\"\']/", '', $expression));
            return "<?php \$__tmp = \$__call->callFlash(['group'=>" . trim($segments[0]) . "]); foreach(\$__tmp as \$" . trim($segments[1]) . "=>\$" . trim($segments[2]) . "): ?>";
        });
        $this->getBlade()->directive('endflash', function($expression) {
            return "<?php endforeach; ?>";
        });
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('page', function () {
            return $this->app->make(Factory::class);
        });
    }
}