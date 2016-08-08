<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-13 18:34
 */
namespace Notadd\Auth;
use Illuminate\Auth\Access\Gate;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Notadd\Auth\Controllers\Admin\ThirdController;
use Notadd\Auth\Social\SocialManager;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
/**
 * Class AuthServiceProvider
 * @package Notadd\Auth
 */
class AuthServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->router->group(['prefix' => 'admin'], function () {
            $this->router->resource('third', ThirdController::class, [
                'only' => ['index', 'store']
            ]);
        });
    }
    /**
     * @return void
     */
    public function register() {
        $this->registerAuthenticator();
        $this->registerUserResolver();
        $this->registerAccessGate();
        $this->registerRequestRebindHandler();
        $this->app->singleton(SocialManager::class, function () {
            $config = [
                'qq' => [
                    'client_id' => $this->setting->get('third.qq.key'),
                    'client_secret' => $this->setting->get('third.qq.secret'),
                    'redirect' => $this->setting->get('third.qq.callback'),
                ],
                'weibo' => [
                    'client_id' => $this->setting->get('third.weibo.key'),
                    'client_secret' => $this->setting->get('third.weibo.secret'),
                    'redirect' => $this->setting->get('third.weibo.callback'),
                ],
                'wechat' => [
                    'client_id' => $this->setting->get('third.weixin.key'),
                    'client_secret' => $this->setting->get('third.weixin.secret'),
                    'redirect' => $this->setting->get('third.weixin.callback'),
                ],
            ];
            return new SocialManager($config);
        });
    }
    /**
     * @return void
     */
    protected function registerAuthenticator() {
        $this->app->singleton('auth', function ($app) {
            $app['auth.loaded'] = true;
            return new AuthManager($app);
        });
        $this->app->singleton('auth.driver', function ($app) {
            return $app['auth']->guard();
        });
    }
    /**
     * @return void
     */
    protected function registerUserResolver() {
        $this->app->bind(AuthenticatableContract::class, function ($app) {
            return call_user_func($app['auth']->userResolver());
        });
    }
    /**
     * @return void
     */
    protected function registerAccessGate() {
        $this->app->singleton(GateContract::class, function ($app) {
            return new Gate($app, function () use ($app) {
                return call_user_func($app['auth']->userResolver());
            });
        });
    }
    /**
     * @return void
     */
    protected function registerRequestRebindHandler() {
        $this->app->rebinding('request', function ($app, $request) {
            $request->setUserResolver(function ($guard = null) use ($app) {
                return call_user_func($app['auth']->userResolver(), $guard);
            });
        });
    }
}