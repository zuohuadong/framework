<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 15:17
 */
namespace Notadd\Menu\Controllers\Admin;
use Illuminate\Http\Request;
use Notadd\Admin\Controllers\AbstractAdminController;
use Notadd\Menu\Models\Menu;
use Notadd\Menu\Models\MenuGroup;
use Notadd\Menu\Requests\MenuGroupCreateRequest;
use Notadd\Menu\Requests\MenuGroupEditRequest;
/**
 * Class GroupController
 * @package Notadd\Menu\Controllers\Admin
 */
class GroupController extends AbstractAdminController {
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) {
        $group = MenuGroup::find($id);
        $group->delete();
        return $this->redirect->to('admin/menu');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $this->share('group', MenuGroup::findOrFail($id));
        return $this->view('menu.group.edit');
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $groups = MenuGroup::all();
        $this->share('count', $groups->count());
        $this->share('groups', $groups);
        return $this->view('menu.group.index');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id) {
        $items = Menu::whereParentId(0)->whereGroupId($id)->orderBy('order_id')->get();
        $this->share('count', $items->count());
        $this->share('group', MenuGroup::find($id));
        $this->share('items', $items);
        $this->share('parent_id', 0);
        return $this->view('menu.group.show');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function sort($id) {
        $items = Menu::whereParentId(0)->whereGroupId($id)->orderBy('order_id')->get();
        $this->share('group', MenuGroup::find($id));
        $this->share('items', $items);
        return $this->view('menu.group.sort');
    }
    /**
     * @param $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sorting($id, Request $request) {
        if(is_array($request->get('order')) && $request->get('order')) {
            foreach($request->get('order') as $key=>$value) {
                if(Menu::whereGroupId($id)->whereId($key)->count()) {
                    $menu = Menu::find($key);
                    $menu->update(['order_id' => $value]);
                }
            }
        }
        return $this->redirect->back();
    }
    /**
     * @param \Notadd\Menu\Requests\MenuGroupCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MenuGroupCreateRequest $request) {
        $group = new MenuGroup();
        $group->create($request->all());
        return $this->redirect->back();
    }
    /**
     * @param \Notadd\Menu\Requests\MenuGroupEditRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MenuGroupEditRequest $request, $id) {
        $group = MenuGroup::findOrFail($id);
        $group->update($request->all());
        return $this->redirect->to("admin/menu");
    }
}