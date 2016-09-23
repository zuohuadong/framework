<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:23
 */
namespace Notadd\Foundation\Passport;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Client
 * @package Notadd\Foundation\Passport
 */
class Client extends Model {
    /**
     * @var string
     */
    protected $table = 'oauth_clients';
    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var array
     */
    protected $hidden = [
        'secret',
    ];
    /**
     * @var array
     */
    protected $casts = [
        'personal_access_client' => 'bool',
        'password_client' => 'bool',
        'revoked' => 'bool',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function authCodes() {
        return $this->hasMany(AuthCode::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens() {
        return $this->hasMany(Token::class);
    }
    /**
     * @return bool
     */
    public function firstParty() {
        return $this->personal_access_client || $this->password_client;
    }
}