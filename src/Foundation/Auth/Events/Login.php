<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:12
 */
namespace Notadd\Foundation\Auth\Events;
use Illuminate\Queue\SerializesModels;
/**
 * Class Login
 * @package Notadd\Foundation\Auth\Events
 */
class Login {
    use SerializesModels;
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;
    /**
     * @var bool
     */
    public $remember;
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param bool $remember
     */
    public function __construct($user, $remember) {
        $this->user = $user;
        $this->remember = $remember;
    }
}