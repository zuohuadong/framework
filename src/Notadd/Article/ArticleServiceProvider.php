<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 16:03
 */
namespace Notadd\Article;
use Notadd\Article\Controllers\Admin\ArticleController as AdminArticleController;
use Notadd\Article\Controllers\ArticleController;
use Notadd\Article\Models\Article as ArticleModel;
use Notadd\Article\Observers\ArticleObserver;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
/**
 * Class ArticleServiceProvider
 * @package Notadd\Article
 */
class ArticleServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->router->resource('article', AdminArticleController::class);
            $this->router->post('article/{id}/delete', AdminArticleController::class . '@delete');
            $this->router->get('article/{id}/move', AdminArticleController::class . '@move');
            $this->router->post('article/{id}/moving', AdminArticleController::class . '@moving');
            $this->router->post('article/{id}/restore', AdminArticleController::class . '@restore');
            $this->router->post('article/select', AdminArticleController::class . '@select');
        });
        $this->router->resource('article', ArticleController::class);
        ArticleModel::observe(ArticleObserver::class);
    }
    /**
     * @return void
     */
    public function register() {
    }
}