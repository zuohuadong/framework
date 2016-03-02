<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Flash\Controllers\Admin;
use Notadd\Admin\Controllers\AbstractAdminController;
use Notadd\Flash\Models\FlashItem;
use Notadd\Flash\Requests\FlashItemRequest;
/**
 * Class ItemController
 * @package Notadd\Flash\Controllers\Admin
 */
class ItemController extends AbstractAdminController {
    public function destroy($id) {
        FlashItem::findOrFail($id)->delete();
        return $this->redirect->back();
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $this->share('item', FlashItem::findOrFail($id));
        return $this->view('flash.item.edit');
    }
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status($id) {
        $item = FlashItem::findOrFail($id);
        $item->update(['enabled' => !$item->enabled]);
        return $this->redirect->back();
    }
    /**
     * @param \Notadd\Flash\Requests\FlashItemRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FlashItemRequest $request) {
        $item = new FlashItem();
        $item = $item->create($request->all());
        return $this->redirect->to('admin/flash/item/' . $item->id . '/edit');
    }
    /**
     * @param $id
     * @param \Notadd\Flash\Requests\FlashItemRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, FlashItemRequest $request) {
        $item = FlashItem::findOrFail($id);
        $item->update($request->all());
        return $this->redirect->back();
    }
}