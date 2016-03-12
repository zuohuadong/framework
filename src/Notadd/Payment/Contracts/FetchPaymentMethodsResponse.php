<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:52
 */
namespace Notadd\Payment\Contracts;
/**
 * Interface FetchPaymentMethodsResponse
 * @package Notadd\Payment\Contracts
 */
interface FetchPaymentMethodsResponse extends Response {
    /**
     * @return \Notadd\Payment\Commons\PaymentMethod[]
     */
    public function getPaymentMethods();
}