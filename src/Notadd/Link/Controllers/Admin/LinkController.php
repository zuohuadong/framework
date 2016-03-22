<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 13:54
 */
namespace Notadd\Link\Controllers\Admin;
use Illuminate\Http\Request;
use Notadd\Admin\Controllers\AbstractAdminController;
use Notadd\Link\Models\Link;
/**
 * Class LinkController
 * @package Notadd\Link\Controllers\Admin
 */
class LinkController extends AbstractAdminController {
    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id) {
        $link = Link::findOrFail($id);
        $link->delete();
        return $this->redirect->to('admin/link');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $this->share('link', Link::findOrFail($id));
        return $this->view('link.edit');
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->share('count', Link::count());
        $this->share('links', Link::all());
        return $this->view('link.index');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $link = new Link();
        $link->create($request->all());
        return $this->redirect->to('admin/link');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id) {
        $link = Link::findOrFail($id);
        $link->update($request->all());
        return $this->redirect->back();
    }
}