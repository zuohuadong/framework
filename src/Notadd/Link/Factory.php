<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 14:15
 */
namespace Notadd\Link;
use Illuminate\Support\Collection;
/**
 * Class Factory
 * @package Notadd\Link
 */
class Factory {
    /**
     * @param $limit
     * @return \Illuminate\Support\Collection
     */
    public function handle(int $limit) {
        $data = new Collection();
        return $data;
    }
}