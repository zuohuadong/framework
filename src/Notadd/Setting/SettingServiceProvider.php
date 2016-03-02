<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-29 16:11
 */
namespace Notadd\Setting;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Traits\InjectRouterTrait;
use Notadd\Setting\Controllers\Admin\ConfigController;
/**
 * Class SettingServiceProvider
 * @package Notadd\Setting
 */
class SettingServiceProvider extends ServiceProvider {
    use InjectRouterTrait;
    /**
     * @return array
     */
    public function boot() {
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->getRouter()->get('site', ConfigController::class . '@getSite');
            $this->getRouter()->post('site', ConfigController::class . '@postSite');
            $this->getRouter()->get('seo', ConfigController::class . '@getSeo');
            $this->getRouter()->post('seo', ConfigController::class . '@postSeo');
        });
    }
    /**
     * @return array
     */
    public function provides() {
        return ['setting'];
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('setting', function () {
            return $this->app->make(Factory::class);
        });
    }
}