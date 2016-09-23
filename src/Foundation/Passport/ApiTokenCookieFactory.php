<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:19
 */
namespace Notadd\Foundation\Passport;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Config\Repository as Config;
/**
 * Class ApiTokenCookieFactory
 * @package Notadd\Foundation\Passport
 */
class ApiTokenCookieFactory {
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;
    /**
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;
    /**
     * ApiTokenCookieFactory constructor.
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Illuminate\Contracts\Encryption\Encrypter $encrypter
     */
    public function __construct(Config $config, Encrypter $encrypter) {
        $this->config = $config;
        $this->encrypter = $encrypter;
    }
    /**
     * @param $userId
     * @param $csrfToken
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public function make($userId, $csrfToken) {
        $config = $this->config->get('session');
        $expiration = Carbon::now()->addMinutes($config['lifetime']);
        return new Cookie('laravel_token', $this->createToken($userId, $csrfToken, $expiration), $expiration, $config['path'], $config['domain'], $config['secure'], true);
    }
    /**
     * @param $userId
     * @param $csrfToken
     * @param \Carbon\Carbon $expiration
     * @return string
     */
    protected function createToken($userId, $csrfToken, Carbon $expiration) {
        return JWT::encode([
            'sub' => $userId,
            'csrf' => $csrfToken,
            'expiry' => $expiration->getTimestamp(),
        ], $this->encrypter->getKey());
    }
}