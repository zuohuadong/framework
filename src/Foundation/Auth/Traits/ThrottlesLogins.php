<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-26 11:19
 */
namespace Notadd\Foundation\Auth\Traits;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Notadd\Foundation\Auth\Events\Lockout;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Class ThrottlesLogins
 * @package Notadd\Foundation\Auth\Traits
 */
trait ThrottlesLogins {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request) {
        return $this->limiter()->tooManyAttempts($this->throttleKey($request), 5, 1);
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return int
     */
    protected function incrementLoginAttempts(Request $request) {
        $this->limiter()->hit($this->throttleKey($request));
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Notadd\Foundation\Http\
     */
    protected function sendLockoutResponse(Request $request) {
        $seconds = $this->limiter()->availableIn($this->throttleKey($request));
        $message = Lang::get('auth.throttle', ['seconds' => $seconds]);
        return redirect()->back()->withInput($request->only($this->username(), 'remember'))->withErrors([$this->username() => $message]);
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return void
     */
    protected function clearLoginAttempts(Request $request) {
        $this->limiter()->clear($this->throttleKey($request));
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return void
     */
    protected function fireLockoutEvent(Request $request) {
        event(new Lockout($request));
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return string
     */
    protected function throttleKey(Request $request) {
        return Str::lower($request->input($this->username())) . '|' . $request->ip();
    }
    /**
     * @return \Illuminate\Cache\RateLimiter
     */
    protected function limiter() {
        return app(RateLimiter::class);
    }
}