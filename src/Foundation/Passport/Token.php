<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:36
 */
namespace Notadd\Foundation\Passport;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Token
 * @package Notadd\Foundation\Passport
 */
class Token extends Model {
    /**
     * @var string
     */
    protected $table = 'oauth_access_tokens';
    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var array
     */
    protected $casts = [
        'scopes' => 'array',
        'revoked' => 'bool',
    ];
    /**
     * @var array
     */
    protected $dates = [
        'expires_at',
    ];
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client() {
        return $this->belongsTo(Client::class);
    }
    /**
     * @param $scope
     * @return bool
     */
    public function can($scope) {
        return in_array('*', $this->scopes) || array_key_exists($scope, array_flip($this->scopes));
    }
    /**
     * @param $scope
     * @return bool
     */
    public function cant($scope) {
        return !$this->can($scope);
    }
    /**
     * @return void
     */
    public function revoke() {
        $this->forceFill(['revoked' => true])->save();
    }
    /**
     * @return bool
     */
    public function transient() {
        return false;
    }
}