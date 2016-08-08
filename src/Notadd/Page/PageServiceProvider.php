<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 16:29
 */
namespace Notadd\Page;
use Illuminate\Routing\Events\RouteMatched;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Notadd\Page\Controllers\Admin\PageController as AdminPageController;
use Notadd\Page\Controllers\PageController;
use Notadd\Page\Models\Page as PageModel;
use Notadd\Page\Observers\PageObserver;
/**
 * Class PageServiceProvider
 * @package Notadd\Page
 */
class PageServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $pages = PageModel::whereEnabled(true)->get();
        foreach($pages as $value) {
            if($this->setting->get('site.home') !== 'page_' . $value->id) {
                if($value->alias) {
                    $page = new Page($value->id);
                    $this->router->get($page->getRouting(), function() use ($page) {
                        return $this->app->call(PageController::class . '@show', ['id' => $page->getPageId()]);
                    });
                }
            }
        }
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->router->resource('page', AdminPageController::class);
            $this->router->post('page/{id}/delete', AdminPageController::class . '@delete');
            $this->router->get('page/{id}/move', AdminPageController::class . '@move');
            $this->router->post('page/{id}/moving', AdminPageController::class . '@moving');
            $this->router->post('page/{id}/restore', AdminPageController::class . '@restore');
            $this->router->get('page/{id}/sort', AdminPageController::class . '@sort');
            $this->router->post('page/{id}/sorting', AdminPageController::class . '@sorting');
        });
        $this->router->resource('page', PageController::class);
        $this->loadViewsFrom($this->app->basePath() . '/resources/views/pages/', 'page');
        $this->events->listen(RouteMatched::class, function () {
            $this->view->share('__call', $this->app->make('page'));
        });
        $this->blade->directive('call', function($expression) {
            return "<?php \$__tmp = \$__call->call{$expression}; foreach(\$__tmp as \$key=>\$value): ?>";
        });
        $this->blade->directive('endcall', function($expression) {
            return "<?php endforeach; ?>";
        });
        $this->blade->directive('article', function($expression) {
            $segments = explode(',', preg_replace("/[\(\)\\\"\']/", '', $expression));
            return "<?php \$__tmp = \$__call->article('" . trim($segments[0]) . "', " . trim($segments[1]) ."); foreach(\$__tmp as \$" . trim($segments[2]) . "=>\$" . trim($segments[3]) . "): ?>";
        });
        $this->blade->directive('endarticle', function($expression) {
            return "<?php endforeach; ?>";
        });
        PageModel::observe(PageObserver::class);
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