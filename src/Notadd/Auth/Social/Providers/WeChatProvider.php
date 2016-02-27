<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-13 21:48
 */
namespace Notadd\Auth\Social\Providers;
use Notadd\Auth\Social\AccessToken;
use Notadd\Auth\Social\Contracts\AccessToken as AccessTokenContract;
use Notadd\Auth\Social\Contracts\Provider as ProviderContract;
use Notadd\Auth\Social\User;
/**
 * Class WeChatProvider
 * @package Notadd\Auth\Social\Providers
 */
class WeChatProvider extends Provider implements ProviderContract {
    /**
     * @var string
     */
    protected $baseUrl = 'https://api.weixin.qq.com/sns';
    /**
     * @var int
     */
    protected $openId;
    /**
     * @var array
     */
    protected $scopes = ['snsapi_login'];
    /**
     * @param string $state
     * @return string
     */
    protected function getAuthUrl($state) {
        $path = 'authorize';
        if(in_array('snsapi_login', $this->scopes)) {
            $path = 'qrconnect';
        }
        return $this->buildAuthUrlFromBase("https://open.weixin.qq.com/connect/{$path}", $state);
    }
    /**
     * @param string $url
     * @param string $state
     * @return string
     */
    protected function buildAuthUrlFromBase($url, $state) {
        $session = $this->request->getSession();
        $query = http_build_query($this->getCodeFields($state), '', '&', $this->encodingType);
        return $url . '?' . $query . '#wechat_redirect';
    }
    /**
     * @param null $state
     * @return array
     */
    protected function getCodeFields($state = null) {
        return [
            'appid' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'response_type' => 'code',
            'scope' => $this->formatScopes($this->scopes, $this->scopeSeparator),
            'state' => $state,
        ];
    }
    /**
     * @return string
     */
    protected function getTokenUrl() {
        return $this->baseUrl . '/oauth2/access_token';
    }
    /**
     * @param \Notadd\Auth\Social\Contracts\AccessToken $token
     * @return mixed
     */
    protected function getUserByToken(AccessTokenContract $token) {
        $response = $this->getHttpClient()->get($this->baseUrl . '/userinfo', [
            'query' => [
                'access_token' => $token->getToken(),
                'openid' => $token['openid'],
                'lang' => 'zh_CN',
            ],
        ]);
        return json_decode($response->getBody(), true);
    }
    /**
     * @param array $user
     * @return \Notadd\Auth\Social\User
     */
    protected function mapUserToObject(array $user) {
        return new User([
            'id' => $this->arrayItem($user, 'openid'),
            'name' => $this->arrayItem($user, 'nickname'),
            'nickname' => $this->arrayItem($user, 'nickname'),
            'avatar' => $this->arrayItem($user, 'headimgurl'),
            'email' => null,
        ]);
    }
    /**
     * @param string $code
     * @return array
     */
    protected function getTokenFields($code) {
        return [
            'appid' => $this->clientId,
            'secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];
    }
    /**
     * @param string $code
     * @return \Notadd\Auth\Social\AccessToken
     */
    public function getAccessToken($code) {
        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
            'query' => $this->getTokenFields($code),
        ]);
        return $this->parseAccessToken($response->getBody()->getContents());
    }
    /**
     * @param \Psr\Http\Message\StreamInterface $body
     * @return \Notadd\Auth\Social\AccessToken
     */
    protected function parseAccessToken($body) {
        return new AccessToken(json_decode($body, true));
    }
    /**
     * @param $response
     * @return string
     */
    protected function removeCallback($response) {
        if(strpos($response, 'callback') !== false) {
            $lpos = strpos($response, '(');
            $rpos = strrpos($response, ')');
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }
        return $response;
    }
}