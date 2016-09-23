<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:33
 */
namespace Notadd\Foundation\Passport;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
/**
 * Class PersonalAccessTokenFactory
 * @package Notadd\Foundation\Passport
 */
class PersonalAccessTokenFactory {
    /**
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    protected $server;
    /**
     * @var \Notadd\Foundation\Passport\ClientRepository
     */
    protected $clients;
    /**
     * @var \Notadd\Foundation\Passport\TokenRepository
     */
    protected $tokens;
    /**
     * @var \Lcobucci\JWT\Parser
     */
    protected $jwt;
    /**
     * PersonalAccessTokenFactory constructor.
     * @param \League\OAuth2\Server\AuthorizationServer $server
     * @param \Notadd\Foundation\Passport\ClientRepository $clients
     * @param \Notadd\Foundation\Passport\TokenRepository $tokens
     * @param \Lcobucci\JWT\Parser $jwt
     */
    public function __construct(AuthorizationServer $server, ClientRepository $clients, TokenRepository $tokens, JwtParser $jwt) {
        $this->jwt = $jwt;
        $this->tokens = $tokens;
        $this->server = $server;
        $this->clients = $clients;
    }
    /**
     * @param $userId
     * @param $name
     * @param array $scopes
     * @return \Notadd\Foundation\Passport\PersonalAccessTokenResult
     */
    public function make($userId, $name, array $scopes = []) {
        $response = $this->dispatchRequestToAuthorizationServer($this->createRequest($this->clients->personalAccessClient(), $userId, $scopes));
        $token = tap($this->findAccessToken($response), function ($token) use ($userId, $name) {
            $this->tokens->save($token->forceFill([
                'user_id' => $userId,
                'name' => $name,
            ]));
        });
        return new PersonalAccessTokenResult($response['access_token'], $token);
    }
    /**
     * @param $client
     * @param $userId
     * @param array $scopes
     * @return \Zend\Diactoros\ServerRequest
     */
    protected function createRequest($client, $userId, array $scopes) {
        return (new ServerRequest)->withParsedBody([
            'grant_type' => 'personal_access',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'user_id' => $userId,
            'scope' => implode(' ', $scopes),
        ]);
    }
    /**
     * @param \Zend\Diactoros\ServerRequest $request
     * @return mixed
     */
    protected function dispatchRequestToAuthorizationServer(ServerRequest $request) {
        return json_decode($this->server->respondToAccessTokenRequest($request, new Response)->getBody()->__toString(), true);
    }
    /**
     * @param array $response
     * @return \Notadd\Foundation\Passport\Token
     */
    protected function findAccessToken(array $response) {
        return $this->tokens->find($this->jwt->parse($response['access_token'])->getClaim('jti'));
    }
}