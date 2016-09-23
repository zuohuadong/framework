<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:44
 */
namespace Notadd\Foundation\Passport\Bridges\Repositories;
use DateTime;
use Illuminate\Database\Connection;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Notadd\Foundation\Passport\Bridges\AccessToken;
use Notadd\Foundation\Passport\Traits\FormatsScopesForStorage;
/**
 * Class AccessTokenRepository
 * @package Notadd\Foundation\Passport\Bridges
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface {
    use FormatsScopesForStorage;
    /**
     * @var \Illuminate\Database\Connection
     */
    protected $database;
    /**
     * AccessTokenRepository constructor.
     * @param \Illuminate\Database\Connection $database
     */
    public function __construct(Connection $database) {
        $this->database = $database;
    }
    /**
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     * @param array $scopes
     * @param null $userIdentifier
     * @return \Notadd\Foundation\Passport\Bridges\AccessToken
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null) {
        return new AccessToken($userIdentifier, $scopes);
    }
    /**
     * @param \League\OAuth2\Server\Entities\AccessTokenEntityInterface $accessTokenEntity
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity) {
        $this->database->table('oauth_access_tokens')->insert([
            'id' => $accessTokenEntity->getIdentifier(),
            'user_id' => $accessTokenEntity->getUserIdentifier(),
            'client_id' => $accessTokenEntity->getClient()->getIdentifier(),
            'scopes' => $this->formatScopesForStorage($accessTokenEntity->getScopes()),
            'revoked' => false,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
            'expires_at' => $accessTokenEntity->getExpiryDateTime(),
        ]);
    }
    /**
     * @param string $tokenId
     */
    public function revokeAccessToken($tokenId) {
        $this->database->table('oauth_access_tokens')->where('id', $tokenId)->update(['revoked' => true]);
    }
    /**
     * @param string $tokenId
     * @return bool
     */
    public function isAccessTokenRevoked($tokenId) {
        return $this->database->table('oauth_access_tokens')->where('id', $tokenId)->where('revoked', 1)->exists();
    }
}