<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-21 12:13
 */
namespace Notadd\Article\Events;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Notadd\Article\Models\Article;
/**
 * Class AfterArticleUpdate
 * @package Notadd\Article\Events
 */
class AfterArticleUpdate {
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private $application;
    /**
     * @var \Notadd\Article\Models\Article
     */
    protected $model;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $view;
    /**
     * AfterArticleUpdate constructor.
     * @param \Illuminate\Contracts\Foundation\Application $application
     * @param \Illuminate\Contracts\View\Factory $view
     * @param \Notadd\Article\Models\Article $article
     */
    public function __construct(Application $application, Factory $view, Article $article) {
        $this->application = $application;
        $this->model = $article;
        $this->view = $view;
    }
    /**
     * @return mixed
     */
    public function getId() {
        return $this->model->getAttribute('id');
    }
    /**
     * @return mixed
     */
    public function getArticle() {
        return $this->model->getAttribute('title');
    }
    /**
     * @return \Notadd\Article\Models\Article
     */
    public function getModel() {
        return $this->model;
    }
    /**
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value) {
        $this->model->setAttribute($key, $value);
    }
}