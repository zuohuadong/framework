<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 17:10
 */
namespace Notadd\Foundation\Composer;
use Illuminate\Support\Composer;
use Illuminate\Support\ServiceProvider;
/**
 * Class ComposerServiceProvider
 * @package Notadd\Foundation\Composer
 */
class ComposerServiceProvider extends ServiceProvider {
    /**
     * @var bool
     */
    protected $defer = true;
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('composer', function ($app) {
            return new Composer($app['files'], $app->basePath());
        });
    }
    /**
     * @return array
     */
    public function provides() {
        return ['composer'];
    }
}