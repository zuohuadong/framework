<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-24 18:15
 */
namespace Notadd\Foundation\Auth\Models;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Notadd\Foundation\Auth\Access\Authorizable;
use Notadd\Foundation\Auth\Passwords\CanResetPassword;
use Notadd\Foundation\Auth\Traits\Authenticatable;
/**
 * Class User
 * @package Notadd\Foundation\Auth\Models
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {
    use Authenticatable, Authorizable, CanResetPassword;
}