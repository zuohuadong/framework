<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:53
 */
namespace Notadd\Payment\Contracts;
/**
 * Interface FetchIssuersResponse
 * @package Notadd\Payment\Contracts
 */
interface FetchIssuersResponse extends Response {
    /**
     * @return \Notadd\Payment\Commons\Issuer[]
     */
    public function getIssuers();
}