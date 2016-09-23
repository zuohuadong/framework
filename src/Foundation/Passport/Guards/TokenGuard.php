<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:57
 */
namespace Notadd\Foundation\Passport\Guards;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Encryption\Encrypter;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Notadd\Foundation\Passport\Repositories\ClientRepository;
use Notadd\Foundation\Passport\Repositories\TokenRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Class TokenGuard
 * @package Notadd\Foundation\Passport\Guards
 */
class TokenGuard {
    /**
     * @var \League\OAuth2\Server\ResourceServer
     */
    protected $server;
    /**
     * @var \Illuminate\Contracts\Auth\UserProvider
     */
    protected $provider;
    /**
     * @var \Notadd\Foundation\Passport\Repositories\TokenRepository
     */
    protected $tokens;
    /**
     * @var \Notadd\Foundation\Passport\Repositories\ClientRepository
     */
    protected $clients;
    /**
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;
    /**
     * TokenGuard constructor.
     * @param \League\OAuth2\Server\ResourceServer $server
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     * @param \Notadd\Foundation\Passport\Repositories\TokenRepository $tokens
     * @param \Notadd\Foundation\Passport\Repositories\ClientRepository $clients
     * @param \Illuminate\Contracts\Encryption\Encrypter $encrypter
     */
    public function __construct(ResourceServer $server, UserProvider $provider, TokenRepository $tokens, ClientRepository $clients, Encrypter $encrypter) {
        $this->server = $server;
        $this->tokens = $tokens;
        $this->clients = $clients;
        $this->provider = $provider;
        $this->encrypter = $encrypter;
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Exception|\League\OAuth2\Server\Exception\OAuthServerException|null|void
     */
    public function user(Request $request) {
        if($request->getHeader('Authorization')) {
            return $this->authenticateViaBearerToken($request);
        } elseif($request->getCookieParams()['laravel_token']) {
            return $this->authenticateViaCookie($request);
        }
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Exception|\League\OAuth2\Server\Exception\OAuthServerException|null|void
     */
    protected function authenticateViaBearerToken(Request $request) {
        try {
            $request = $this->server->validateAuthenticatedRequest($request);
            $user = $this->provider->retrieveById($request->getAttribute('oauth_user_id'));
            if(!$user) {
                return;
            }
            $token = $this->tokens->find($request->getAttribute('oauth_access_token_id'));
            $clientId = $psr->getAttribute('oauth_client_id');
            if($this->clients->revoked($clientId)) {
                return;
            }
            return $token ? $user->withAccessToken($token) : null;
        } catch(OAuthServerException $e) {
            return $e;
        }
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    protected function authenticateViaCookie(Request $request) {
        try {
            $token = $this->decodeJwtTokenCookie($request);
        } catch(Exception $e) {
            return;
        }
        if(!$this->validCsrf($token, $request) || time() >= $token['expiry']) {
            return;
        }
        if($user = $this->provider->retrieveById($token['sub'])) {
            return $user->withAccessToken(new TransientToken);
        }
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return array
     */
    protected function decodeJwtTokenCookie(Request $request) {
        return (array)JWT::decode($this->encrypter->decrypt($request->getCookieParams()['laravel_token']), $this->encrypter->getKey(), ['HS256']);
    }
    /**
     * @param $token
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return bool
     */
    protected function validCsrf($token, Request $request) {
        return isset($token['csrf']) && hash_equals($token['csrf'], (string)$request->getHeader('X-CSRF-TOKEN'));
    }
}