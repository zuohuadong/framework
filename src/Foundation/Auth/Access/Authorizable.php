<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-26 10:10
 */
namespace Notadd\Foundation\Auth\Access;
use Illuminate\Contracts\Auth\Access\Gate;
/**
 * Class Authorizable
 * @package Notadd\Foundation\Auth\Access
 */
trait Authorizable {
    /**
     * @param string $ability
     * @param array|mixed $arguments
     * @return bool
     */
    public function can($ability, $arguments = []) {
        return app(Gate::class)->forUser($this)->check($ability, $arguments);
    }
    /**
     * @param string $ability
     * @param array|mixed $arguments
     * @return bool
     */
    public function cant($ability, $arguments = []) {
        return !$this->can($ability, $arguments);
    }
    /**
     * @param string $ability
     * @param array|mixed $arguments
     * @return bool
     */
    public function cannot($ability, $arguments = []) {
        return $this->cant($ability, $arguments);
    }
}