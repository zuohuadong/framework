<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-12 17:13
 */
namespace Notadd\Flash;
use Illuminate\Support\Collection;
use Notadd\Flash\Models\FlashItem;
/**
 * Class Factory
 * @package Notadd\Flash
 */
class Factory {
    /**
     * @param $id
     * @return mixed
     */
    public function handle($id) {
        $data = FlashItem::whereGroupId($id)->get();
        $list = new Collection();
        foreach($data as $item) {
            $list->push(new Flash($item->getAttribute('id')));
        }
        return $list;
    }
}