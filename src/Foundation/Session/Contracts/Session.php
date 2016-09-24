<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-24 16:13
 */
namespace Notadd\Foundation\Session\Contracts;
use Illuminate\Session\SessionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Interface Session
 * @package Notadd\Foundation\Session\Contracts
 */
interface Session extends SessionInterface {
    /**
     * Set the request on the handler instance.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @return void
     */
    public function setPsrRequestOnHandler(Request $request);
}