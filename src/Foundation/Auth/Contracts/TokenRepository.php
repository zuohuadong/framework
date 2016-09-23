<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:24
 */
namespace Notadd\Foundation\Auth\Contracts;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
/**
 * Interface TokenRepository
 * @package Notadd\Foundation\Auth\Contracts
 */
interface TokenRepository {
    /**
     * @param \Illuminate\Contracts\Auth\CanResetPassword $user
     * @return string
     */
    public function create(CanResetPasswordContract $user);
    /**
     * @param \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param string $token
     * @return bool
     */
    public function exists(CanResetPasswordContract $user, $token);
    /**
     * @param string $token
     * @return void
     */
    public function delete($token);
    /**
     * @return void
     */
    public function deleteExpired();
}