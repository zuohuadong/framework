<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 13:21
 */
namespace Notadd\Foundation\Passport;
use DateInterval;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\ResourceServer;
use Notadd\Foundation\Auth\Guards\RequestGuard;
use Notadd\Foundation\Passport\Bridges\PersonalAccessGrant;
use Notadd\Foundation\Passport\Bridges\Repositories\AccessTokenRepository;
use Notadd\Foundation\Passport\Bridges\Repositories\AuthCodeRepository;
use Notadd\Foundation\Passport\Bridges\Repositories\ClientRepository;
use Notadd\Foundation\Passport\Bridges\Repositories\RefreshTokenRepository;
use Notadd\Foundation\Passport\Bridges\Repositories\ScopeRepository;
use Notadd\Foundation\Passport\Bridges\Repositories\UserRepository;
use Notadd\Foundation\Passport\Guards\TokenGuard;
use Notadd\Foundation\Passport\Repositories\TokenRepository;
/**
 * Class PassportServiceProvider
 * @package Notadd\Foundation\Passport
 */
class PassportServiceProvider extends ServiceProvider {
    /**
     * @return \League\OAuth2\Server\Grant\AuthCodeGrant
     */
    protected function buildAuthCodeGrant() {
        return new AuthCodeGrant($this->app->make(AuthCodeRepository::class), $this->app->make(RefreshTokenRepository::class), new DateInterval('PT10M'));
    }
    /**
     * @return \League\OAuth2\Server\Grant\AuthCodeGrant
     */
    protected function makeAuthCodeGrant() {
        return tap($this->buildAuthCodeGrant(), function (AuthCodeGrant $grant) {
            $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
        });
    }
    /**
     * @return \League\OAuth2\Server\AuthorizationServer
     */
    public function makeAuthorizationServer() {
        return new AuthorizationServer($this->app->make(ClientRepository::class), $this->app->make(AccessTokenRepository::class), $this->app->make(ScopeRepository::class), 'file://' . Passport::keyPath('oauth-private.key'), 'file://' . Passport::keyPath('oauth-public.key'));
    }
    /**
     * @param array $config
     * @return \Notadd\Foundation\Auth\Guards\RequestGuard
     */
    protected function makeGuard(array $config) {
        return new RequestGuard(function ($request) use ($config) {
            return (new TokenGuard($this->app->make(ResourceServer::class), $this->app->make('auth')->createUserProvider($config['provider']), new TokenRepository, $this->app->make(ClientRepository::class), $this->app->make('encrypter')))->user($request);
        }, $this->app['request']);
    }
    /**
     * @return \League\OAuth2\Server\Grant\PasswordGrant
     */
    protected function makePasswordGrant() {
        $grant = new PasswordGrant($this->app->make(UserRepository::class), $this->app->make(RefreshTokenRepository::class));
        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
        return $grant;
    }
    /**
     * @return \League\OAuth2\Server\Grant\RefreshTokenGrant
     */
    protected function makeRefreshTokenGrant() {
        $repository = $this->app->make(RefreshTokenRepository::class);
        return tap(new RefreshTokenGrant($repository), function (RefreshTokenGrant $grant) {
            $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
        });
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton(AuthorizationServer::class, function () {
            return tap($this->makeAuthorizationServer(), function (AuthorizationServer $server) {
                $server->enableGrantType($this->makeAuthCodeGrant(), Passport::tokensExpireIn());
                $server->enableGrantType($this->makeRefreshTokenGrant(), Passport::tokensExpireIn());
                $server->enableGrantType($this->makePasswordGrant(), Passport::tokensExpireIn());
                $server->enableGrantType(new PersonalAccessGrant, new DateInterval('P100Y'));
                $server->enableGrantType(new ClientCredentialsGrant, Passport::tokensExpireIn());
            });
        });
        $this->app->singleton(ResourceServer::class, function () {
            return new ResourceServer($this->app->make(AccessTokenRepository::class), 'file://' . Passport::keyPath('oauth-public.key'));
        });
        $this->app->make('auth')->extend('passport', function ($app, $name, array $config) {
            return tap($this->makeGuard($config), function ($guard) {
                $this->app->refresh('request', $guard, 'setRequest');
            });
        });
    }
}