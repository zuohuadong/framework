<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Category\Controllers;
use Notadd\Category\Category;
use Notadd\Category\Events\OnCategoryShow;
use Notadd\Foundation\Routing\Controller;
/**
 * Class CategoryController
 * @package Notadd\Category\Controllers
 */
class CategoryController extends Controller {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        return $this->view('');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id) {
        $category = new Category($id);
        $this->events->fire(new OnCategoryShow($this->app, $this->view, $category));
        $this->seo->setTitleMeta($category->getTitle() . ' - {sitename}');
        $this->seo->setDescriptionMeta($category->getDescription());
        $this->seo->setKeywordsMeta($category->getKeywords());
        $this->share('category', $category->getModel());
        $this->share('links', $category->getLinks());
        $this->share('list', $category->getList());
        $this->share('name', $category->getTitle());
        $this->share('relations', $category->getRelationCategoryList());
        return $this->view($category->getShowTemplate());
    }
}