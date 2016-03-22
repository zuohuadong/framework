<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:49
 */
namespace Notadd\Payment\Contracts;
/**
 * Interface RedirectResponse
 * @package Notadd\Payment\Contracts
 */
interface RedirectResponse extends Response {
    /**
     * @return string
     */
    public function getRedirectUrl();
    /**
     * @return string
     */
    public function getRedirectMethod();
    /**
     * @return array
     */
    public function getRedirectData();
    /**
     * @return void
     */
    public function redirect();
}