<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 15:37
 */
namespace Notadd\Foundation\Auth\Guards;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Notadd\Foundation\Auth\Traits\GuardHelpers;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Class TokenGuard
 * @package Notadd\Foundation\Auth
 */
class TokenGuard implements Guard {
    use GuardHelpers;
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;
    /**
     * @var string
     */
    protected $inputKey;
    /**
     * @var string
     */
    protected $storageKey;
    /**
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(UserProvider $provider, Request $request) {
        $this->request = $request;
        $this->provider = $provider;
        $this->inputKey = 'api_token';
        $this->storageKey = 'api_token';
    }
    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user() {
        if(!is_null($this->user)) {
            return $this->user;
        }
        $user = null;
        $token = $this->getTokenForRequest();
        if(!empty($token)) {
            $user = $this->provider->retrieveByCredentials([$this->storageKey => $token]);
        }
        return $this->user = $user;
    }
    /**
     * @return string
     */
    public function getTokenForRequest() {
        $token = $this->request->getQueryParams()[$this->inputKey];
        if(empty($token)) {
            $token = $this->request->getHeader('Authorization');
        }
        if(empty($token)) {
            $token = $this->request->getHeader('PHP_AUTH_PW');
        }
        return $token;
    }
    /**
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = []) {
        if(empty($credentials[$this->inputKey])) {
            return false;
        }
        $credentials = [$this->storageKey => $credentials[$this->inputKey]];
        if($this->provider->retrieveByCredentials($credentials)) {
            return true;
        }
        return false;
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return $this
     */
    public function setRequest(Request $request) {
        $this->request = $request;
        return $this;
    }
}