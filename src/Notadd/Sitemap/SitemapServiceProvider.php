<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-17 11:51
 */
namespace Notadd\Sitemap;
use Notadd\Article\Models\Article;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Notadd\Sitemap\Controllers\Admin\SitemapController as AdminSitemapController;
/**
 * Class SitemapServiceProvider
 * @package Notadd\Sitemap
 */
class SitemapServiceProvider extends AbstractServiceProvider {
    /**
     * @var \Notadd\Sitemap\Sitemap
     */
    protected $sitemap;
    /**
     * @return void
     */
    public function boot() {
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->router->resource('sitemap', AdminSitemapController::class);
        });
        $this->sitemap = $this->app->make('sitemap');
        $this->events->listen('kernel.handled', function() {
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
            return new Sitemap($this->app, $this->config->get('sitemap'));
        });
    }
}