<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-26 15:16
 */
namespace Notadd\Foundation\Routing\Contracts;
/**
 * Interface ControllerContract
 * @package Notadd\Foundation\Http\Contracts
 */
interface Controller {
    /**
     * @param string $method
     * @return array
     */
    public function getMiddlewareForMethod($method);
}