<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:25
 */
namespace Notadd\Foundation\Image;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;
use Notadd\Foundation\Traits\InjectConfigTrait;
/**
 * Class ImageServiceProvider
 * @package Notadd\Foundation\Image
 */
class ImageServiceProvider extends ServiceProvider {
    use InjectConfigTrait;
    /**
     * @return bool
     */
    private function cacheIsInstalled() {
        return class_exists('Intervention\\Image\\ImageCache');
    }
    /**
     * @return void
     */
    public function boot() {
        $this->cacheIsInstalled() ? $this->bootstrapImageCache() : null;
    }
    /**
     * @return void
     */
    private function bootstrapImageCache() {
    }
    /**
     * @return void
     */
    public function register() {
        $this->app['image'] = $this->app->share(function () {
            return new ImageManager($this->getConfig()->get('image'));
        });
        $this->app->alias('image', 'Intervention\Image\ImageManager');
    }
    /**
     * @return array
     */
    public function provides() {
        return [
            'image'
        ];
    }
}