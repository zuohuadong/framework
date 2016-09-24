<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-24 15:53
 */
namespace Notadd\Foundation\Session;
use Illuminate\Session\SessionManager as IlluminateSessionManager;
/**
 * Class SessionManager
 * @package Notadd\Foundation\Session
 */
class SessionManager extends IlluminateSessionManager {
    /**
     * @param \SessionHandlerInterface $handler
     * @return \Illuminate\Session\EncryptedStore|\Notadd\Foundation\Session\Store
     */
    protected function buildSession($handler) {
        if($this->app['config']['session.encrypt']) {
            return new EncryptedStore($this->app['config']['session.cookie'], $handler, $this->app['encrypter']);
        } else {
            return new Store($this->app['config']['session.cookie'], $handler);
        }
    }
}