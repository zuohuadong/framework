<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 15:40
 */
namespace Notadd\Foundation\Auth\Exceptions;
use Exception;
/**
 * Class AuthenticationException
 * @package Notadd\Foundation\Auth\Exceptions
 */
class AuthenticationException extends Exception {
    /**
     * @param string $message
     */
    public function __construct($message = 'Unauthenticated.') {
        parent::__construct($message);
    }
}