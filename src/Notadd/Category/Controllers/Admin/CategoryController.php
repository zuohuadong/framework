<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 15:48
 */
namespace Notadd\Category\Controllers\Admin;
use Illuminate\Http\Request;
use Notadd\Foundation\Abstracts\AbstractAdminController;
use Notadd\Category\Events\BeforeCategoryDelete;
use Notadd\Category\Models\Category;
use Notadd\Category\Requests\CategoryCreateRequest;
use Notadd\Category\Requests\CategoryEditRequest;
/**
 * Class CategoryController
 * @package Notadd\Category\Controllers\Admin
 */
class CategoryController extends AbstractAdminController {
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) {
        $category = Category::findOrFail($id);
        $this->events->fire(new BeforeCategoryDelete($this->app, $category));
        $category->delete();
        return $this->redirect->back();
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $category = Category::findOrFail($id);
        $crumbs = [];
        Category::buildCrumb($category->parent_id, $crumbs);
        $this->share('category', $category);
        $this->share('crumbs', $crumbs);
        $this->share('types', $category->getTypes());
        return $this->view($category->getAdminTemplate()->get('edit'));
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $categories = Category::whereParentId('0')->get();
        $this->share('categories', $categories);
        $this->share('count', $categories->count());
        $this->share('crumbs', []);
        $this->share('id', 0);
        return $this->view('category.show');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function move($id) {
        $crumbs = [];
        Category::buildCrumb($id, $crumbs);
        $category = Category::findOrFail($id);
        $list = $category->where('id', '!=', $id)->get();
        $this->share('crumbs', $crumbs);
        $this->share('id', $id);
        $this->share('category', $category);
        $this->share('list', $list);
        return $this->view('category.move');
    }
    /**
     * @param $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function moving($id, Request $request) {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return $this->redirect->to('admin/category/' . $category->getAttribute('parent_id'));
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id) {
        $crumbs = [];
        Category::buildCrumb($id, $crumbs);
        $categories = Category::whereParentId($id)->get();
        $this->share('categories', $categories);
        $this->share('count', $categories->count());
        $this->share('crumbs', $crumbs);
        $this->share('id', $id);
        return $this->view('category.show');
    }
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status($id) {
        $category = Category::findOrFail($id);
        $category->update(['enabled' => !$category->enabled]);
        return $this->redirect->back();
    }
    /**
     * @param \Notadd\Category\Requests\CategoryCreateRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(CategoryCreateRequest $request) {
        $category = new Category();
        $category->create($request->all());
        return $this->redirect->back();
    }
    /**
     * @param \Notadd\Category\Requests\CategoryEditRequest $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(CategoryEditRequest $request, $id) {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return $this->redirect->back();
    }
}