<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-08 15:50
 */
namespace Notadd\Foundation\Http;
use Illuminate\Support\ServiceProvider;
use Notadd\Admin\AdminServiceProvider;
use Notadd\Article\ArticleServiceProvider;
use Notadd\Attachment\AttachmentServiceProvider;
use Notadd\Auth\AuthServiceProvider;
use Notadd\Cache\CacheServiceProvider;
use Notadd\Category\CategoryServiceProvider;
use Notadd\Develop\DevelopServiceProvider;
use Notadd\Editor\EditorServiceProvider;
use Notadd\Extension\ExtensionServiceProvider;
use Notadd\Flash\FlashServiceProvider;
use Notadd\Image\ImageServiceProvider;
use Notadd\Link\LinkServiceProvider;
use Notadd\Menu\MenuServiceProvider;
use Notadd\Page\PageServiceProvider;
use Notadd\Payment\PaymentServiceProvider;
use Notadd\Search\SearchServiceProvider;
use Notadd\Setting\SettingServiceProvider;
use Notadd\Sitemap\SitemapServiceProvider;
use Notadd\Theme\ThemeServiceProvider;
/**
 * Class AppServiceProvider
 * @package Notadd\Foundation\Http
 */
class AppServiceProvider extends ServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->app->register(AdminServiceProvider::class);
        $this->app->register(ArticleServiceProvider::class);
        $this->app->register(AttachmentServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(CacheServiceProvider::class);
        $this->app->register(CategoryServiceProvider::class);
        $this->app->register(DevelopServiceProvider::class);
        $this->app->register(EditorServiceProvider::class);
        $this->app->register(ExtensionServiceProvider::class);
        $this->app->register(FlashServiceProvider::class);
        $this->app->register(HttpServiceProvider::class);
        $this->app->register(ImageServiceProvider::class);
        $this->app->register(LinkServiceProvider::class);
        $this->app->register(MenuServiceProvider::class);
        $this->app->register(PageServiceProvider::class);
        $this->app->register(PaymentServiceProvider::class);
        $this->app->register(SearchServiceProvider::class);
        $this->app->register(SitemapServiceProvider::class);
        $this->app->register(ThemeServiceProvider::class);
    }
    /**
     * @return void
     */
    public function register() {
    }
}