<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:53
 */
namespace Notadd\Foundation\Passport\Bridges\Repositories;
use Illuminate\Database\Connection;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Notadd\Foundation\Passport\Bridges\RefreshToken;
/**
 * Class RefreshTokenRepository
 * @package Notadd\Foundation\Passport\Bridges
 */
class RefreshTokenRepository implements RefreshTokenRepositoryInterface {
    /**
     * @var \Illuminate\Database\Connection
     */
    protected $database;
    /**
     * RefreshTokenRepository constructor.
     * @param \Illuminate\Database\Connection $database
     */
    public function __construct(Connection $database) {
        $this->database = $database;
    }
    /**
     * @return \Notadd\Foundation\Passport\Bridges\RefreshToken
     */
    public function getNewRefreshToken() {
        return new RefreshToken;
    }
    /**
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity) {
        $this->database->table('oauth_refresh_tokens')->insert([
            'id' => $refreshTokenEntity->getIdentifier(),
            'access_token_id' => $refreshTokenEntity->getAccessToken()->getIdentifier(),
            'revoked' => false,
            'expires_at' => $refreshTokenEntity->getExpiryDateTime(),
        ]);
    }
    /**
     * @param string $tokenId
     */
    public function revokeRefreshToken($tokenId) {
        $this->database->table('oauth_refresh_tokens')->where('id', $tokenId)->update(['revoked' => true]);
    }
    /**
     * @param string $tokenId
     * @return bool
     */
    public function isRefreshTokenRevoked($tokenId) {
        return $this->database->table('oauth_refresh_tokens')->where('id', $tokenId)->where('revoked', 1)->exists();
    }
}