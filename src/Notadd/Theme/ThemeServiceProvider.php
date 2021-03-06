<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-29 16:31
 */
namespace Notadd\Theme;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Console\Kernel;
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
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
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
        $this->getEvents()->listen(RouteMatched::class, function () use ($default) {
            $this->getView()->share('__theme', $this->getTheme());
            $this->getTheme()->getThemeList()->each(function(Theme $theme) use($default) {
                $alias = $theme->getAlias();
                if($alias == $default && $this->getTheme()->getThemeList()->has($alias)) {
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
        $this->getEvents()->listen('kernel.handled', function() {
            if($this->app->inDebugMode()) {
                $command = $this->app->make(Kernel::class)->find('vendor:publish');
                $input = new ArrayInput([
                    '--force' => true
                ]);
                $output = new BufferedOutput();
                $command->run($input, $output);
                $this->app->make('log')->info('调试状态下发布静态到公共目录的结果：' . $output->fetch());
            }
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
    }
}