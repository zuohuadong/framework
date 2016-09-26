<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-26 10:46
 */
namespace Notadd\Foundation\Auth\Traits;
/**
 * Class RedirectsUsers
 * @package Notadd\Foundation\Auth\Traits
 */
trait RedirectsUsers {
    /**
     * @return string
     */
    public function redirectPath() {
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}