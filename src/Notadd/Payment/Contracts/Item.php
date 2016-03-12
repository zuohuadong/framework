<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:10
 */
namespace Notadd\Payment\Contracts;
/**
 * Interface Item
 * @package Notadd\Payment\Contracts
 */
interface Item {
    /**
     * @return string
     */
    public function getName();
    /**
     * @return string
     */
    public function getDescription();
    /**
     * @return mixed
     */
    public function getQuantity();
    /**
     * @return mixed
     */
    public function getPrice();
}