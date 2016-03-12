<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:51
 */
namespace Notadd\Payment\Contracts;
/**
 * Interface Notification
 * @package Notadd\Payment\Contracts
 */
interface Notification extends Message {
    const STATUS_COMPLETED = 'completed';
    const STATUS_PENDING = 'pending';
    const STATUS_FAILED = 'failed';
    /**
     * @return string
     */
    public function getTransactionReference();
    /**
     * @return string
     */
    public function getTransactionStatus();
    /**
     * @return string
     */
    public function getMessage();
}