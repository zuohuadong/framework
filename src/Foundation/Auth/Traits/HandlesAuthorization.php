<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:16
 */
namespace Notadd\Foundation\Auth\Traits;
use Notadd\Foundation\Auth\Access\Response;
use Notadd\Foundation\Auth\Exceptions\AuthorizationException;
/**
 * Class HandlesAuthorization
 * @package Notadd\Foundation\Auth\Traits
 */
trait HandlesAuthorization {
    /**
     * @param null $message
     * @return \Notadd\Foundation\Auth\Access\Response
     */
    protected function allow($message = null) {
        return new Response($message);
    }
    /**
     * @param string $message
     * @throws \Notadd\Foundation\Auth\Exceptions\AuthorizationException
     */
    protected function deny($message = 'This action is unauthorized.') {
        throw new AuthorizationException($message);
    }
}