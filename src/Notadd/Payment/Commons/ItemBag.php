<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:42
 */
namespace Notadd\Payment\Commons;
use Countable;
use IteratorAggregate;
use Notadd\Payment\Contracts\Item as ItemContract;
/**
 * Class ItemBag
 * @package Notadd\Payment\Commons
 */
class ItemBag implements IteratorAggregate, Countable {
    /**
     * @var
     */
    protected $items;
    /**
     * ItemBag constructor.
     * @param array $items
     */
    public function __construct(array $items = array()) {
        $this->replace($items);
    }
    /**
     * @return array
     */
    public function all() {
        return $this->items;
    }
    /**
     * @param array $items
     */
    public function replace(array $items = array()) {
        $this->items = array();
        foreach($items as $item) {
            $this->add($item);
        }
    }
    /**
     * @param $item
     */
    public function add($item) {
        if($item instanceof ItemContract) {
            $this->items[] = $item;
        } else {
            $this->items[] = new Item($item);
        }
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->items);
    }
    /**
     * @return int
     */
    public function count() {
        return count($this->items);
    }
}