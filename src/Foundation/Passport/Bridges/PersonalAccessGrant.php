<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:52
 */
namespace Notadd\Foundation\Passport\Bridges;
use DateInterval;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
/**
 * Class PersonalAccessGrant
 * @package Notadd\Foundation\Passport\Bridges
 */
class PersonalAccessGrant extends AbstractGrant {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface $responseType
     * @param \DateInterval $accessTokenTTL
     * @return \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface
     */
    public function respondToAccessTokenRequest(ServerRequestInterface $request, ResponseTypeInterface $responseType, DateInterval $accessTokenTTL) {
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request));
        $scopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client);
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $this->getRequestParameter('user_id', $request), $scopes);
        $responseType->setAccessToken($accessToken);
        return $responseType;
    }
    /**
     * @return string
     */
    public function getIdentifier() {
        return 'personal_access';
    }
}