<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:46
 */
namespace Notadd\Foundation\Passport\Bridges\Repositories;
use Illuminate\Database\Connection;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Notadd\Foundation\Passport\Bridges\AuthCode;
use Notadd\Foundation\Passport\Traits\FormatsScopesForStorage;
/**
 * Class AuthCodeRepository
 * @package Notadd\Foundation\Passport\Bridges
 */
class AuthCodeRepository implements AuthCodeRepositoryInterface {
    use FormatsScopesForStorage;
    /**
     * The database connection.
     * @var \Illuminate\Database\Connection
     */
    protected $database;
    /**
     * AuthCodeRepository constructor.
     * @param \Illuminate\Database\Connection $database
     */
    public function __construct(Connection $database) {
        $this->database = $database;
    }
    /**
     * @return \Notadd\Foundation\Passport\Bridges\AuthCode
     */
    public function getNewAuthCode() {
        return new AuthCode;
    }
    /**
     * @param \League\OAuth2\Server\Entities\AuthCodeEntityInterface $authCodeEntity
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity) {
        $this->database->table('oauth_auth_codes')->insert([
            'id' => $authCodeEntity->getIdentifier(),
            'user_id' => $authCodeEntity->getUserIdentifier(),
            'client_id' => $authCodeEntity->getClient()->getIdentifier(),
            'scopes' => $this->formatScopesForStorage($authCodeEntity->getScopes()),
            'revoked' => false,
            'expires_at' => $authCodeEntity->getExpiryDateTime(),
        ]);
    }
    /**
     * @param string $codeId
     */
    public function revokeAuthCode($codeId) {
        $this->database->table('oauth_auth_codes')->where('id', $codeId)->update(['revoked' => true]);
    }
    /**
     * @param string $codeId
     * @return bool
     */
    public function isAuthCodeRevoked($codeId) {
        return $this->database->table('oauth_auth_codes')->where('id', $codeId)->where('revoked', 1)->exists();
    }
}