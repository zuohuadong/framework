<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-20 20:39
 */
namespace Notadd\Foundation\Http\Exceptions;
use Exception;
/**
 * Class MethodNotAllowedException
 * @package Notadd\Foundation\Http\Exceptions
 */
class MethodNotAllowedException extends Exception {
    /**
     * MethodNotAllowedException constructor.
     * @param null $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = null, $code = 405, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}