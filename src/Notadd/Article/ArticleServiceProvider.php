<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 16:03
 */
namespace Notadd\Article;
use Illuminate\Support\ServiceProvider;
use Notadd\Article\Models\Article as ArticleModel;
use Notadd\Article\Observers\ArticleObserver;
use Notadd\Foundation\Traits\InjectRouterTrait;
/**
 * Class ArticleServiceProvider
 * @package Notadd\Article
 */
class ArticleServiceProvider extends ServiceProvider {
    use InjectRouterTrait;
    /**
     * @return void
     */
    public function boot() {
        $this->getRouter()->group(['namespace' => 'Notadd\Article\Controllers'], function () {
            $this->getRouter()->group(['middleware' => 'auth.admin', 'namespace' => 'Admin', 'prefix' => 'admin'], function () {
                $this->getRouter()->resource('article', 'ArticleController');
                $this->getRouter()->post('article/{id}/delete', 'ArticleController@delete');
                $this->getRouter()->post('article/{id}/restore', 'ArticleController@restore');
                $this->getRouter()->post('article/select', 'ArticleController@select');
            });
            $this->getRouter()->resource('article', 'ArticleController');
        });
        ArticleModel::observe(ArticleObserver::class);
    }
    /**
     * @return void
     */
    public function register() {
    }
}