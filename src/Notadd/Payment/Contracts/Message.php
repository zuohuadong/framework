<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:46
 */
namespace Notadd\Payment\Contracts;
/**
 * Interface Message
 * @package Notadd\Payment\Contracts
 */
interface Message {
    /**
     * @return mixed
     */
    public function getData();
}