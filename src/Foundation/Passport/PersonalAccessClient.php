<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:33
 */
namespace Notadd\Foundation\Passport;
use Illuminate\Database\Eloquent\Model;
/**
 * Class PersonalAccessClient
 * @package Notadd\Foundation\Passport
 */
class PersonalAccessClient extends Model {
    /**
     * @var string
     */
    protected $table = 'oauth_personal_access_clients';
    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client() {
        return $this->belongsTo(Client::class);
    }
}
