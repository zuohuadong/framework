<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:12
 */
namespace Notadd\Foundation\Auth\Events;
/**
 * Class Failed
 * @package Notadd\Foundation\Auth\Events
 */
class Failed {
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public $user;
    /**
     * @var array
     */
    public $credentials;
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @param array $credentials
     */
    public function __construct($user, $credentials) {
        $this->user = $user;
        $this->credentials = $credentials;
    }
}