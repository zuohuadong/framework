<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:11
 */
namespace Notadd\Foundation\Auth\Events;
use Illuminate\Queue\SerializesModels;
/**
 * Class Authenticated
 * @package Notadd\Foundation\Auth\Events
 */
class Authenticated {
    use SerializesModels;
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     */
    public function __construct($user) {
        $this->user = $user;
    }
}