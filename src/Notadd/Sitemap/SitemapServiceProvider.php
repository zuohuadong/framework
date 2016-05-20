<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-17 11:51
 */
namespace Notadd\Sitemap;
use Illuminate\Support\ServiceProvider;
use Notadd\Article\Models\Article;
use Notadd\Foundation\Traits\InjectConfigTrait;
use Notadd\Foundation\Traits\InjectEventsTrait;
use Notadd\Foundation\Traits\InjectRouterTrait;
use Notadd\Sitemap\Controllers\Admin\SitemapController as AdminSitemapController;
/**
 * Class SitemapServiceProvider
 * @package Notadd\Sitemap
 */
class SitemapServiceProvider extends ServiceProvider {
    use InjectConfigTrait, InjectEventsTrait, InjectRouterTrait;
    /**
     * @var \Notadd\Sitemap\Sitemap
     */
    protected $sitemap;
    /**
     * @return void
     */
    public function boot() {
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->getRouter()->resource('sitemap', AdminSitemapController::class);
        });
        $this->sitemap = $this->app->make('sitemap');
        $this->getEvents()->listen('kernel.handled', function() {
            $articles = Article::orderBy('created_at', 'desc')->take(100)->get();
            $articles->each(function(Article $article) {
                $this->sitemap->add($this->app['url']->to('article/' . $article->getAttribute('id')), $article->getAttribute('updated_at'), 0.8, 'daily', [], $article->getAttribute('title'));
            });
            $this->sitemap->store('xml', 'sitemap');
        });
    }
    /**
     * @return array
     */
    public function provides() {
        return ['sitemap'];
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('sitemap', function () {
            return new Sitemap($this->app, $this->getConfig()->get('sitemap'));
        });
    }
}