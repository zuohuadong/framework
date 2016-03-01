<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-29 16:31
 */
namespace Notadd\Theme;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Traits\InjectBladeTrait;
use Notadd\Foundation\Traits\InjectCookieTrait;
use Notadd\Foundation\Traits\InjectEventsTrait;
use Notadd\Foundation\Traits\InjectRequestTrait;
use Notadd\Foundation\Traits\InjectRouterTrait;
use Notadd\Foundation\Traits\InjectSettingTrait;
use Notadd\Foundation\Traits\InjectThemeTrait;
use Notadd\Foundation\Traits\InjectViewTrait;
use Notadd\Theme\Controllers\Admin\PublishController;
use Notadd\Theme\Controllers\Admin\ThemeController;
/**
 * Class ThemeServiceProvider
 * @package Notadd\Theme
 */
class ThemeServiceProvider extends ServiceProvider {
    use InjectBladeTrait, InjectCookieTrait, InjectEventsTrait, InjectSettingTrait, InjectRequestTrait, InjectRouterTrait, InjectThemeTrait, InjectViewTrait;
    /**
     * @return void
     */
    public function boot() {
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->getRouter()->post('theme/cookie', function() {
                $default = $this->getRequest()->input('theme');
                $this->getCookie()->queue($this->getCookie()->forever('admin-theme', $default));
            });
            $this->getRouter()->resource('theme', ThemeController::class);
            $this->getRouter()->resource('theme/publish', PublishController::class);
        });
        $default = $this->getSetting()->get('site.theme', 'default');
        $this->getEvents()->listen('router.matched', function () use ($default) {
            $this->getView()->share('__theme', $this->getTheme());
            $this->getTheme()->getThemeList()->each(function(Theme $theme) use($default) {
                $alias = $theme->getAlias();
                if($alias == $default) {
                    $this->loadViewsFrom($theme->getViewPath(), 'themes');
                }
                $this->loadViewsFrom($theme->getViewPath(), $alias);
                $publishData = $theme->getPublishData();
                if($publishData->count()) {
                    $publishData->each(function($item, $key) use($alias) {
                        $this->publishes([
                            $key => $item
                        ], 'theme');
                        $this->publishes([
                            $key => $item
                        ], $alias);
                    });
                }
            });
        });
        $this->getBlade()->directive('css', function($expression) {
            return "<?php \$__theme->registerCss{$expression}; ?>";
        });
        $this->getBlade()->directive('js', function($expression) {
            return "<?php \$__theme->registerJs{$expression}; ?>";
        });
        $this->getBlade()->directive('output', function($expression) {
            return "<?php echo \$__theme->outputInBlade{$expression}; ?>";
        });
    }
    /**
     * @return array
     */
    public function provides() {
        return ['theme'];
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('theme', function () {
            return $this->app->make(Factory::class);
        });
        $this->app->singleton('theme.finder', function () {
            return $this->app->make(FileFinder::class);
        });
        $this->app->singleton('theme.material', function() {
            return $this->app->make(Material::class);
        });
    }
}