<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-01 13:45
 */
namespace Notadd\Flash\Controllers\Admin;
use Notadd\Admin\Controllers\AbstractAdminController;
use Notadd\Flash\Models\Flash;
use Notadd\Flash\Models\FlashItem;
use Notadd\Flash\Requests\FlashCreateRequest;
use Notadd\Flash\Requests\FlashEditRequest;
/**
 * Class FlashController
 * @package Notadd\Flash\Controllers\Admin
 */
class GroupController extends AbstractAdminController {
    public function destroy($id) {
        $group = Flash::findOrFail($id);
        $group->delete();
        return $this->redirect->to('admin/flash');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $this->share('group', Flash::findOrFail($id));
        return $this->view('flash.group.edit');
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->share('groups', Flash::all());
        return $this->view('flash.group.index');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id) {
        $model = FlashItem::whereGroupId($id);
        $this->share('count', $model->count());
        $this->share('group', Flash::findOrFail($id));
        $this->share('items', $model->get());
        return $this->view('flash.group.show');
    }
    /**
     * @param \Notadd\Flash\Requests\FlashCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FlashCreateRequest $request) {
        $group = new Flash();
        $group->create($request->all());
        return $this->redirect->back();
    }
    public function update($id, FlashEditRequest $request) {
        $group = Flash::findOrFail($id);
        $group->update($request->all());
        return $this->redirect->to('admin/flash');
    }
}