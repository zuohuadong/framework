<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-24 18:13
 */
namespace Notadd\Member\Models;
use Notadd\Foundation\Auth\Models\User as Authenticatable;
/**
 * Class Member
 * @package Notadd\Member\Models
 */
class Member extends Authenticatable {
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}