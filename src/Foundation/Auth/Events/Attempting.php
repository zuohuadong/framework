<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:11
 */
namespace Notadd\Foundation\Auth\Events;
/**
 * Class Attempting
 * @package Notadd\Foundation\Auth\Events
 */
class Attempting {
    /**
     * @var array
     */
    public $credentials;
    /**
     * @var bool
     */
    public $remember;
    /**
     * @var bool
     */
    public $login;
    /**
     * @param array $credentials
     * @param bool $remember
     * @param bool $login
     */
    public function __construct($credentials, $remember, $login) {
        $this->login = $login;
        $this->remember = $remember;
        $this->credentials = $credentials;
    }
}