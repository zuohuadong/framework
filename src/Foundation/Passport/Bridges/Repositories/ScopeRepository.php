<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:54
 */
namespace Notadd\Foundation\Passport\Bridges\Repositories;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Notadd\Foundation\Passport\Bridges\Scope;
use Notadd\Foundation\Passport\Passport;
/**
 * Class ScopeRepository
 * @package Notadd\Foundation\Passport\Bridges
 */
class ScopeRepository implements ScopeRepositoryInterface {
    /**
     * @param string $identifier
     * @return \Notadd\Foundation\Passport\Bridges\Scope
     */
    public function getScopeEntityByIdentifier($identifier) {
        if(Passport::hasScope($identifier)) {
            return new Scope($identifier);
        }
    }
    /**
     * @param array $scopes
     * @param string $grantType
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     * @param null $userIdentifier
     * @return array
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null) {
        if($grantType !== 'password') {
            $scopes = collect($scopes)->reject(function ($scope) {
                return trim($scope->getIdentifier()) === '*';
            })->values()->all();
        }
        return collect($scopes)->filter(function ($scope) {
            return Passport::hasScope($scope->getIdentifier());
        })->values()->all();
    }
}