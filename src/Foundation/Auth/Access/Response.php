<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:17
 */
namespace Notadd\Foundation\Auth\Access;
/**
 * Class Response
 * @package Notadd\Foundation\Auth\Access
 */
class Response {
    /**
     * @var string|null
     */
    protected $message;
    /**
     * @param  string|null $message
     */
    public function __construct($message = null) {
        $this->message = $message;
    }
    /**
     * @return string|null
     */
    public function message() {
        return $this->message;
    }
    /**
     * @return string
     */
    public function __toString() {
        return $this->message();
    }
}