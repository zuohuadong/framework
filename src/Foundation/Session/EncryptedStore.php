<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-24 16:48
 */
namespace Notadd\Foundation\Session;
use Illuminate\Session\EncryptedStore as IlluminateEncryptedStore;
use Notadd\Foundation\Session\Contracts\Session as SessionContract;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Class EncryptedStore
 * @package Notadd\Foundation\Session
 */
class EncryptedStore extends IlluminateEncryptedStore implements SessionContract {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function setPsrRequestOnHandler(Request $request) {
        if($this->handlerNeedsRequest()) {
            $this->handler->setRequest($request);
        }
    }
}