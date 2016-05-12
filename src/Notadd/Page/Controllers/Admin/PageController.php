<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 17:19
 */
namespace Notadd\Page\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Notadd\Admin\Controllers\AbstractAdminController;
use Notadd\Page\Models\Page;
use Notadd\Page\Requests\PageCreateRequest;
use Notadd\Page\Requests\PageEditRequest;
/**
 * Class PageController
 * @package Notadd\Page\Controllers\Admin
 */
class PageController extends AbstractAdminController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create() {
        $page = new Page();
        $this->share('templates', $page->getTemplateList());
        return $this->view('page.create');
    }
    /**
     * @param $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request) {
        $request->isMethod('post') && Page::onlyTrashed()->find($id)->forceDelete();
        return $this->redirect->to('admin/page');
    }
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) {
        $page = Page::find($id);
        $page->delete();
        return $this->redirect->back();
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $crumb = [];
        Page::getCrumbMenu($id, $crumb);
        $page = Page::findOrFail($id);
        $this->share('crumbs', $crumb);
        $this->share('page', $page);
        $this->share('templates', $page->getTemplateList());
        return $this->view('page.edit');
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $page = Page::whereParentId(0)->orderBy('created_at', 'desc');
        $this->share('count', $page->count());
        $this->share('crumbs', []);
        $this->share('id', 0);
        $this->share('pages', $page->get());
        return $this->view('page.list');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function move($id) {
        $crumb = [];
        Page::getCrumbMenu($id, $crumb);
        $page = Page::findOrFail($id);
        $list = $page->where('id', '!=', $id)->get();
        $this->share('crumbs', $crumb);
        $this->share('id', $id);
        $this->share('page', $page);
        $this->share('list', $list);
        return $this->view('page.move');
    }
    /**
     * @param $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function moving($id, Request $request) {
        $page = Page::findOrFail($id);
        $page->update($request->all());
        return $this->redirect->to('admin/page/' . $page->getAttribute('parent_id'));
    }
    /**
     * @param $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id, Request $request) {
        $request->isMethod('post') && Page::onlyTrashed()->find($id)->restore();
        return $this->redirect->to('admin/page');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id) {
        $crumb = [];
        Page::getCrumbMenu($id, $crumb);
        $page = Page::whereParentId($id)->orderBy('order_id', 'asc');
        $this->share('count', $page->count());
        $this->share('crumbs', $crumb);
        $this->share('id', $id);
        $this->share('pages', $page->get());
        return $this->view('page.list');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function sort($id) {
        $crumb = [];
        Page::getCrumbMenu($id, $crumb);
        $page = Page::findOrFail($id);
        $pages = Page::whereParentId($id)->orderBy('order_id', 'asc')->get();
        $this->share('count', $page->count());
        $this->share('crumbs', $crumb);
        $this->share('id', $id);
        $this->share('page', $page);
        $this->share('pages', $pages);
        return $this->view('page.sort');
    }
    /**
     * @param $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sorting($id, Request $request) {
        if(is_array($request->get('order')) && $request->get('order')) {
            foreach($request->get('order') as $key => $value) {
                if(Page::whereParentId($id)->whereId($key)->count()) {
                    Page::findOrFail($key)->update(['order_id' => $value]);
                }
            }
        }
        return $this->redirect->back();
    }
    /**
     * @param \Notadd\Page\Requests\PageCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PageCreateRequest $request) {
        if($request->input('parent_id')) {
            if(!Page::whereId($request->input('parent_id'))->count()) {
                return $this->redirect->back();
            }
        }
        Page::create($request->all());
        return $this->redirect->back();
    }
    /**
     * @param \Notadd\Page\Requests\PageEditRequest $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(PageEditRequest $request, $id) {
        $page = Page::findOrFail($id);
        //if($request->hasFile('thumb_image') && $request->file('thumb_image')->isValid()) {
        //    $file_name = Str::random() . '.' . $request->file('thumb_image')->getClientOriginalExtension();
        //    $request->file('thumb_image')->move('uploads/pages/thumbs/', $file_name);
         //   $request->offsetSet('thumb_image', 'uploads/pages/thumbs/' . $file_name);
        //}
        //$request->files->replace();
        $page->update($request->all());
        return $this->redirect->to('admin/page/' . $id . '/edit');
    }
}