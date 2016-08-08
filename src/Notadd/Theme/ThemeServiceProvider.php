<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-29 16:31
 */
namespace Notadd\Theme;
use Illuminate\Routing\Events\RouteMatched;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Notadd\Foundation\Console\Kernel;
use Notadd\Theme\Controllers\Admin\PublishController;
use Notadd\Theme\Controllers\Admin\ThemeController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
/**
 * Class ThemeServiceProvider
 * @package Notadd\Theme
 */
class ThemeServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->router->post('theme/cookie', function() {
                $default = $this->request->input('theme');
                $this->app['cookie']->queue($this->app['cookie']->forever('admin-theme', $default));
            });
            $this->router->resource('theme', ThemeController::class);
            $this->router->resource('theme/publish', PublishController::class);
        });
        $default = $this->setting->get('site.theme', 'default');
        $this->events->listen(RouteMatched::class, function () use ($default) {
            $this->view->share('__theme', $this->app['theme']);
            $this->app['theme']->getThemeList()->each(function(Theme $theme) use($default) {
                $alias = $theme->getAlias();
                if($alias == $default && $this->app['theme']->getThemeList()->has($alias)) {
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
        $this->events->listen('kernel.handled', function() {
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