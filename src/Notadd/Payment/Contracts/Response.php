<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:47
 */
namespace Notadd\Payment\Contracts;
/**
 * Interface Response
 * @package Notadd\Payment\Contracts
 */
interface Response extends Message {
    /**
     * @return mixed
     */
    public function getRequest();
    /**
     * @return boolean
     */
    public function isSuccessful();
    /**
     * @return boolean
     */
    public function isRedirect();
    /**
     * @return boolean
     */
    public function isCancelled();
    /**
     * @return null|string
     */
    public function getMessage();
    /**
     * @return null|string
     */
    public function getCode();
    /**
     * @return null|string
     */
    public function getTransactionReference();
}