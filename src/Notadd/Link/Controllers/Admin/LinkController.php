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
        return $this->redirect->to('admin/link');
    }
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
    public function edit($id) {
        return $this->view('');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $id) {
        return $this->redirect->back('admin/link');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id) {
        return $this->redirect->back('admin/link');
    }
}