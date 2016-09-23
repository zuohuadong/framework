<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:32
 */
namespace Notadd\Foundation\Auth\Traits;
/**
 * Class Authenticatable
 * @package Notadd\Foundation\Auth\Traits
 */
trait Authenticatable {
    /**
     * @return string
     */
    public function getAuthIdentifierName() {
        return $this->getKeyName();
    }
    /**
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->getKey();
    }
    /**
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }
    /**
     * @return string
     */
    public function getRememberToken() {
        return $this->{$this->getRememberTokenName()};
    }
    /**
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value) {
        $this->{$this->getRememberTokenName()} = $value;
    }
    /**
     * @return string
     */
    public function getRememberTokenName() {
        return 'remember_token';
    }
}