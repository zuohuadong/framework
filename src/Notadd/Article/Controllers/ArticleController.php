<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Article\Controllers;
use Notadd\Article\Article;
use Notadd\Article\Events\OnArticleShow;
use Notadd\Foundation\Routing\AbstractController;
/**
 * Class ArticleController
 * @package Notadd\Article\Controllers
 */
class ArticleController extends AbstractController {
    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id) {
        $article = new Article($id);
        $this->events->fire(new OnArticleShow($this->app, $this->view, $article));
        $this->seo->setTitleMeta($article->getTitle() . ' - {sitename}');
        $this->seo->setDescriptionMeta($article->getDescription());
        $this->share('title', $article->getTitle());
        $this->share('content', $article->getContent());
        $this->share('category', $article->getCategory());
        $this->share('relations', $article->getCategory()->getRelationCategoryList());
        return $this->view($article->getShowTemplate());
    }
}