<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:27
 */
namespace Notadd\Foundation\Passport\Traits;
use Illuminate\Container\Container;
use Notadd\Foundation\Passport\Client;
use Notadd\Foundation\Passport\PersonalAccessTokenFactory;
use Notadd\Foundation\Passport\Token;
/**
 * Class HasApiTokens
 * @package Notadd\Foundation\Passport
 */
trait HasApiTokens {
    /**
     * @var \Notadd\Foundation\Passport\Token
     */
    protected $accessToken;
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients() {
        return $this->hasMany(Client::class, 'user_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tokens() {
        return $this->hasMany(Token::class, 'user_id')->orderBy('created_at', 'desc');
    }
    /**
     * @return \Notadd\Foundation\Passport\Token|null
     */
    public function token() {
        return $this->accessToken;
    }
    /**
     * @param string $scope
     * @return bool
     */
    public function tokenCan($scope) {
        return $this->accessToken ? $this->accessToken->can($scope) : false;
    }
    /**
     * @param string $name
     * @param array $scopes
     * @return \Notadd\Foundation\Passport\PersonalAccessTokenResult
     */
    public function createToken($name, array $scopes = []) {
        return Container::getInstance()->make(PersonalAccessTokenFactory::class)->make($this->getKey(), $name, $scopes);
    }
    /**
     * @param \Notadd\Foundation\Passport\Token $accessToken
     * @return $this
     */
    public function withAccessToken($accessToken) {
        $this->accessToken = $accessToken;
        return $this;
    }
}