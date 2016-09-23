<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:57
 */
namespace Notadd\Foundation\Auth;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
/**
 * Class GenericUser
 * @package Notadd\Foundation\Auth
 */
class GenericUser implements UserContract {
    /**
     * @var array
     */
    protected $attributes;
    /**
     * GenericUser constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes) {
        $this->attributes = $attributes;
    }
    /**
     * @return string
     */
    public function getAuthIdentifierName() {
        return 'id';
    }
    /**
     * @return mixed
     */
    public function getAuthIdentifier() {
        $name = $this->getAuthIdentifierName();
        return $this->attributes[$name];
    }
    /**
     * @return string
     */
    public function getAuthPassword() {
        return $this->attributes['password'];
    }
    /**
     * @return string
     */
    public function getRememberToken() {
        return $this->attributes[$this->getRememberTokenName()];
    }
    /**
     * @param string $value
     * @return void
     */
    public function setRememberToken($value) {
        $this->attributes[$this->getRememberTokenName()] = $value;
    }
    /**
     * @return string
     */
    public function getRememberTokenName() {
        return 'remember_token';
    }
    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key) {
        return $this->attributes[$key];
    }
    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value) {
        $this->attributes[$key] = $value;
    }
    /**
     * @param string $key
     * @return bool
     */
    public function __isset($key) {
        return isset($this->attributes[$key]);
    }
    /**
     * @param string $key
     * @return void
     */
    public function __unset($key) {
        unset($this->attributes[$key]);
    }
}