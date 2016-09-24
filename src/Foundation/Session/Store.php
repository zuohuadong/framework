<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-24 15:46
 */
namespace Notadd\Foundation\Session;
use Illuminate\Session\Store as IlluminateStore;
use Notadd\Foundation\Session\Contracts\Session as SessionContract;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
/**
 * Class Store
 * @package Notadd\Foundation\Session
 */
class Store extends IlluminateStore implements SessionContract {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function setPsrRequestOnHandler(Request $request) {
        if($this->handlerNeedsRequest()) {
            $this->handler->setRequest($request);
        }
    }
    /**
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return void
     */
    public function setRequestOnHandler(SymfonyRequest $request) {
    }
}