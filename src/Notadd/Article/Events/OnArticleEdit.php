<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-04-30 19:39
 */
namespace Notadd\Article\Events;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Notadd\Article\Article;
/**
 * Class OnArticleEdit
 * @package Notadd\Article\Events
 */
class OnArticleEdit {
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private $application;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $view;
    /**
     * @var \Notadd\Article\Article
     */
    private $article;
    /**
     * OnArticleEdit constructor.
     * @param \Illuminate\Contracts\Foundation\Application $application
     * @param \Illuminate\Contracts\View\Factory $view
     * @param $id
     */
    public function __construct(Application $application, Factory $view, $id) {
        $this->application = $application;
        $this->view = $view;
        $this->article = new Article($id);
    }
    /**
     * @return \Notadd\Article\Models\Article
     */
    public function getArticle() {
        return $this->article->getModel();
    }
    /**
     * @return \Notadd\Category\Category
     */
    public function getCategory() {
        return $this->article->getCategory();
    }
    /**
     * @param $key
     * @param $value
     */
    public function share($key, $value) {
        $this->view->share($key, $value);
    }
}