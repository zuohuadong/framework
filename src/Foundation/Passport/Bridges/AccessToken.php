<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:41
 */
namespace Notadd\Foundation\Passport\Bridges;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
/**
 * Class AccessToken
 * @package Notadd\Foundation\Passport\Bridges
 */
class AccessToken implements AccessTokenEntityInterface {
    use AccessTokenTrait, EntityTrait, TokenEntityTrait;
    /**
     * AccessToken constructor.
     * @param $userIdentifier
     * @param array $scopes
     */
    public function __construct($userIdentifier, array $scopes = []) {
        $this->setUserIdentifier($userIdentifier);
        foreach($scopes as $scope) {
            $this->addScope($scope);
        }
    }
}