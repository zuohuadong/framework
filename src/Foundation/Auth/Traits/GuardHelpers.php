<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 15:38
 */
namespace Notadd\Foundation\Auth\Traits;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Notadd\Foundation\Auth\Exceptions\AuthenticationException;
/**
 * Class GuardHelpers
 * @package Notadd\Foundation\Auth\Traits
 */
trait GuardHelpers {
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;
    /**
     * @var \Illuminate\Contracts\Auth\UserProvider
     */
    protected $provider;
    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable
     * @throws \Notadd\Foundation\Auth\Exceptions\AuthenticationException
     */
    public function authenticate() {
        if(!is_null($user = $this->user())) {
            return $user;
        }
        throw new AuthenticationException;
    }
    /**
     * @return bool
     */
    public function check() {
        return !is_null($this->user());
    }
    /**
     * @return bool
     */
    public function guest() {
        return !$this->check();
    }
    /**
     * @return int|null
     */
    public function id() {
        if($this->user()) {
            return $this->user()->getAuthIdentifier();
        }
    }
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return $this
     */
    public function setUser(AuthenticatableContract $user) {
        $this->user = $user;
        return $this;
    }
}