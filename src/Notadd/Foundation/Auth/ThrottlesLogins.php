<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-18 16:28
 */
namespace Notadd\Foundation\Auth;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
/**
 * Class ThrottlesLogins
 * @package Notadd\Foundation\Auth
 */
trait ThrottlesLogins {
    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request) {
        return app(RateLimiter::class)->tooManyAttempts($this->getThrottleKey($request), $this->maxLoginAttempts(), $this->lockoutTime() / 60);
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    protected function incrementLoginAttempts(Request $request) {
        app(RateLimiter::class)->hit($this->getThrottleKey($request));
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function retriesLeft(Request $request) {
        return app(RateLimiter::class)->retriesLeft($this->getThrottleKey($request), $this->maxLoginAttempts());
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request) {
        $seconds = app(RateLimiter::class)->availableIn($this->getThrottleKey($request));
        return redirect()->back()->withInput($request->only($this->loginUsername(), 'remember'))->withErrors([
            $this->loginUsername() => $this->getLockoutErrorMessage($seconds),
        ]);
    }
    /**
     * @param int $seconds
     * @return string
     */
    protected function getLockoutErrorMessage($seconds) {
        return trans()->has('auth.throttle') ? trans()->get('auth.throttle', ['seconds' => $seconds]) : 'Too many login attempts. Please try again in ' . $seconds . ' seconds.';
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function clearLoginAttempts(Request $request) {
        app(RateLimiter::class)->clear($this->getThrottleKey($request));
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function getThrottleKey(Request $request) {
        return mb_strtolower($request->input($this->loginUsername())) . '|' . $request->ip();
    }
    /**
     * @return int
     */
    protected function maxLoginAttempts() {
        return property_exists($this, 'maxLoginAttempts') ? $this->maxLoginAttempts : 5;
    }
    /**
     * @return int
     */
    protected function lockoutTime() {
        return property_exists($this, 'lockoutTime') ? $this->lockoutTime : 60;
    }
    /**
     * @param \Illuminate\Http\Request $request
     */
    protected function fireLockoutEvent(Request $request) {
        event(new Lockout($request));
    }
}