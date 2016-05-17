<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-17 11:51
 */
namespace Notadd\Sitemap;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Traits\InjectRouterTrait;
use Notadd\Sitemap\Controllers\Admin\SitemapController as AdminSitemapController;
/**
 * Class SitemapServiceProvider
 * @package Notadd\Sitemap
 */
class SitemapServiceProvider extends ServiceProvider {
    use InjectRouterTrait;
    /**
     * @return void
     */
    public function boot() {
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->getRouter()->resource('sitemap', AdminSitemapController::class);
        });
    }
    /**
     * @return void
     */
    public function register() {
    }
}