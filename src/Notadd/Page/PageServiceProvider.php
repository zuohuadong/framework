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
        $this->getEvents()->listen('router.before', function() {
            $pages = PageModel::whereEnabled(true)->get();
            foreach($pages as $value) {
                if($this->getSetting()->get('site.home') !== 'page_' . $value->id) {
                    if($value->alias) {
                        $page = new Page($value->id);
                        $this->getRouter()->get($page->getRouting(), function() use ($page) {
                            return $this->app->call('Notadd\Page\Controllers\PageController@show', ['id' => $page->getPageId()]);
                        });
                    }
                }
            }
        });
        $this->getRouter()->group(['namespace' => 'Notadd\Page\Controllers'], function () {
            $this->getRouter()->group(['middleware' => 'auth.admin', 'namespace' => 'Admin', 'prefix' => 'admin'], function () {
                $this->getRouter()->resource('page', 'PageController');
                $this->getRouter()->post('page/{id}/delete', 'PageController@delete');
                $this->getRouter()->get('page/{id}/move', 'PageController@move');
                $this->getRouter()->post('page/{id}/moving', 'PageController@moving');
                $this->getRouter()->post('page/{id}/restore', 'PageController@restore');
                $this->getRouter()->get('page/{id}/sort', 'PageController@sort');
                $this->getRouter()->post('page/{id}/sorting', 'PageController@sorting');
            });
            $this->getRouter()->resource('page', 'PageController');
        });
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