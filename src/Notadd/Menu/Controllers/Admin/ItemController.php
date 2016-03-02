<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 15:22
 */
namespace Notadd\Menu\Controllers\Admin;
use Illuminate\Support\Str;
use Notadd\Admin\Controllers\AbstractAdminController;
use Notadd\Menu\Models\Menu;
use Notadd\Menu\Models\MenuGroup;
use Notadd\Menu\Requests\MenuCreateRequest;
/**
 * Class ItemController
 * @package Notadd\Menu\Controllers\Admin
 */
class ItemController extends AbstractAdminController {
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return $this->redirect->back();
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $crumb = [];
        $item = Menu::find($id);
        Menu::buildCrumb($item->parent_id, $crumb);
        $this->share('item', $item);
        $this->share('crumbs', $crumb);
        return $this->view('menu.item.edit');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id) {
        $crumb = [];
        Menu::buildCrumb($id, $crumb);
        $menu = Menu::findOrFail($id);
        $items = Menu::whereParentId($id)->whereGroupId($menu->group_id)->orderBy('order_id')->get();
        $this->share('count', $items->count());
        $this->share('crumbs', $crumb);
        $this->share('group', MenuGroup::find($menu->group_id));
        $this->share('items', $items);
        $this->share('parent_id', $id);
        return $this->view('menu.item.show');
    }
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status($id) {
        $item = Menu::findOrFail($id);
        $item->update(['enabled' => !$item->enabled]);
        return $this->redirect->back();
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function sort($id) {
        $crumb = [];
        Menu::buildCrumb($id, $crumb);
        $parent = Menu::findOrFail($id);
        $items = Menu::whereParentId($id)->orderBy('order_id')->get();
        $this->share('group', MenuGroup::find($parent->group_id));
        $this->share('crumbs', $crumb);
        $this->share('items', $items);
        $this->share('parent', $parent);
        return $this->view('menu.item.sort');
    }
    /**
     * @param $id
     * @param \Notadd\Menu\Controllers\Admin\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sorting($id, Request $request) {
        if(is_array($request->get('order')) && $request->get('order')) {
            foreach($request->get('order') as $key => $value) {
                if(Menu::whereParentId($id)->whereId($key)->count()) {
                    $menu = Menu::find($key);
                    $menu->update(['order_id' => $value]);
                }
            }
        }
        return $this->redirect->back();
    }
    /**
     * @param \Notadd\Menu\Requests\MenuCreateRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(MenuCreateRequest $request) {
        $menu = new Menu();
        $menu->create($request->all());
        return $this->redirect->back();
    }
    /**
     * @param \Notadd\Menu\Requests\MenuCreateRequest $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(MenuCreateRequest $request, $id) {
        $menu = Menu::findOrFail($id);
        $menu->update($request->all());
        return $this->redirect->back();
    }
}