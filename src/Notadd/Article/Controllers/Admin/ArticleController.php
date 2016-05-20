<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 16:12
 */
namespace Notadd\Article\Controllers\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Notadd\Admin\Controllers\AbstractAdminController;
use Notadd\Article\Events\OnArticleEdit;
use Notadd\Article\Models\Article;
use Notadd\Article\Models\ArticleRecommend;
use Notadd\Article\Requests\ArticleCreateRequest;
use Notadd\Article\Requests\ArticleEditRequest;
use Notadd\Category\Models\Category;
/**
 * Class ArticleController
 * @package Notadd\Article\Controllers\Admin
 */
class ArticleController extends AbstractAdminController {
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(Request $request) {
        if(Category::whereEnabled(true)->whereId($request->input('category'))->count()) {
            $category = Category::whereEnabled(true)->whereId($request->input('category'))->firstOrFail();
            $this->share('category', $category);
            return $this->view($category->getArticleTemplate('create'));
        } else {
            return $this->redirect->to('admin/category');
        }
    }
    /**
     * @param $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request) {
        $request->isMethod('post') && Article::onlyTrashed()->find($id)->forceDelete();
        return $this->redirect->to('admin/article');
    }
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) {
        $article = Article::find($id);
        $article->delete();
        ArticleRecommend::whereArticleId($id)->delete();
        return $this->redirect->to('admin/article');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $article = Article::findOrFail($id);
        $category = Category::findOrFail($article->category_id);
        $this->events->fire(new OnArticleEdit($this->app, $this->view, $id));
        $this->share('article', $article);
        $this->share('category', $category);
        return $this->view($category->getArticleTemplate('edit'));
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $articles = Article::with('category')->latest()->paginate(30);
        $articles->setPath($this->app->make('request')->url());
        $this->share('articles', $articles);
        $this->share('category_id', 0);
        $this->share('crumbs', []);
        $this->share('count', Article::count());
        return $this->view('admin::article.list');
    }
    /**
     * @param $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id, Request $request) {
        $request->isMethod('post') && Article::onlyTrashed()->find($id)->restore();
        return $this->redirect->to('admin/article');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id) {
        $crumb = [];
        Category::buildCrumb($id, $crumb);
        $articles = Article::with('category')->whereCategoryId($id)->orderBy('created_at', 'desc')->paginate(30);
        $articles->setPath($this->app->make('request')->url());
        $this->share('articles', $articles);
        $this->share('category_id', $id);
        $this->share('crumbs', $crumb);
        $this->share('count', Article::count());
        return $this->view('admin::article.list');
    }
    /**
     * @param \Notadd\Article\Requests\ArticleCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ArticleCreateRequest $request) {
        $article = new Article();
        $request->offsetSet('user_id', $this->app->make('auth')->user()->id);
        $request->offsetSet('created_at', new Carbon());
        $article->create($request->all());
        return $this->redirect->to('admin/article');
    }
    /**
     * @param \Notadd\Article\Requests\ArticleEditRequest $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(ArticleEditRequest $request, $id) {
        $article = Article::findOrFail($id);
        $request->offsetSet('user_id', $this->app->make('auth')->user()->id);
        $request->offsetSet('created_at', new Carbon($request->offsetGet('created_at')));
        $article->update($request->all());
        return $this->redirect->to('admin/article');
    }
}