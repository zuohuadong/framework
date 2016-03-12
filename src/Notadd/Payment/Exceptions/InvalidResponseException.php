<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:24
 */
namespace Notadd\Payment\Exceptions;
use Notadd\Payment\Contracts\Exception;
/**
 * Class InvalidResponseException
 * @package Notadd\Payment\Exceptions
 */
class InvalidResponseException extends \Exception implements Exception {
    /**
     * InvalidResponseException constructor.
     * @param string $message
     * @param int $code
     * @param null $previous
     */
    public function __construct($message = "Invalid response from payment gateway", $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}