<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-21 17:12
 */
namespace Notadd\Foundation\Http\Exceptions;
use Exception;
/**
 * Class MethodNotFoundException
 * @package Notadd\Foundation\Http\Exceptions
 */
class MethodNotFoundException extends Exception {
    /**
     * RouteNotFoundException constructor.
     * @param null $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = null, $code = 404, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}