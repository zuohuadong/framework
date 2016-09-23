<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:22
 */
namespace Notadd\Foundation\Passport;
use Illuminate\Database\Eloquent\Model;
/**
 * Class AuthCode
 * @package Notadd\Foundation\Passport
 */
class AuthCode extends Model {
    /**
     * @var string
     */
    protected $table = 'oauth_auth_codes';
    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var array
     */
    protected $casts = [
        'revoked' => 'bool',
    ];
    /**
     * @var array
     */
    protected $dates = [
        'expires_at',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function client() {
        return $this->hasMany(Client::class);
    }
}