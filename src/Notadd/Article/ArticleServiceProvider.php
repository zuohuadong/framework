<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 16:03
 */
namespace Notadd\Article;
use Illuminate\Support\ServiceProvider;
use Notadd\Article\Controllers\Admin\ArticleController as AdminArticleController;
use Notadd\Article\Controllers\ArticleController;
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
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->getRouter()->resource('article', AdminArticleController::class);
            $this->getRouter()->post('article/{id}/delete', AdminArticleController::class . '@delete');
            $this->getRouter()->get('article/{id}/move', AdminArticleController::class . '@move');
            $this->getRouter()->post('article/{id}/moving', AdminArticleController::class . '@moving');
            $this->getRouter()->post('article/{id}/restore', AdminArticleController::class . '@restore');
            $this->getRouter()->post('article/select', AdminArticleController::class . '@select');
        });
        $this->getRouter()->resource('article', ArticleController::class);
        ArticleModel::observe(ArticleObserver::class);
    }
    /**
     * @return void
     */
    public function register() {
    }
}