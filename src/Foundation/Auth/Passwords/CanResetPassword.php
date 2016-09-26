<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-26 10:12
 */
namespace Notadd\Foundation\Auth\Passwords;
/**
 * Class CanResetPassword
 * @package Notadd\Foundation\Auth\Passwords
 */
trait CanResetPassword {
    /**
     * @return string
     */
    public function getEmailForPasswordReset() {
        return $this->email;
    }
    /**
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token) {
        //$this->notify(new ResetPasswordNotification($token));
    }
}