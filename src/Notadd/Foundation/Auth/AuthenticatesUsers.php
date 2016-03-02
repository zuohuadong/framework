<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-18 16:28
 */
namespace Notadd\Foundation\Auth;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
/**
 * Class AuthenticatesUsers
 * @package Notadd\Foundation\Auth
 */
trait AuthenticatesUsers {
    use RedirectsUsers;
    /**
     * @return \Illuminate\Http\Response
     */
    public function getLogin() {
        if(view()->exists('auth.authenticate')) {
            return view('auth.authenticate');
        }
        return view('auth.login');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request) {
        $this->validate($request, [
            $this->loginUsername() => 'required',
            'password' => 'required',
        ]);
        $throttles = $this->isUsingThrottlesLoginsTrait();
        if($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        $credentials = $this->getCredentials($request);
        if($this->app->make('auth')->guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }
        if($throttles && !$lockedOut) {
            $this->incrementLoginAttempts($request);
        }
        return redirect()->back()->withInput($request->only($this->loginUsername(), 'remember'))->withErrors([
            $this->loginUsername() => $this->getFailedLoginMessage(),
        ]);
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @param bool $throttles
     * @return \Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request, $throttles) {
        if($throttles) {
            $this->clearLoginAttempts($request);
        }
        if(method_exists($this, 'authenticated')) {
            return $this->authenticated($request, $this->app->make('auth')->guard($this->getGuard())->user());
        }
        return redirect()->intended($this->redirectPath());
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function getCredentials(Request $request) {
        return $request->only($this->loginUsername(), 'password');
    }
    /**
     * @return string
     */
    protected function getFailedLoginMessage() {
        return trans()->has('auth.failed') ? trans()->get('auth.failed') : 'These credentials do not match our records.';
    }
    /**
     * @return \Illuminate\Http\Response
     */
    public function getLogout() {
        $this->app->make('auth')->guard($this->getGuard())->logout();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
    /**
     * @return string
     */
    public function loginPath() {
        return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
    }
    /**
     * @return string
     */
    public function guestMiddleware() {
        $guard = $this->getGuard();
        return $guard ? 'guest:' . $guard : 'guest';
    }
    /**
     * @return string
     */
    public function loginUsername() {
        return property_exists($this, 'username') ? $this->username : 'email';
    }
    /**
     * @return bool
     */
    protected function isUsingThrottlesLoginsTrait() {
        return in_array(ThrottlesLogins::class, class_uses_recursive(get_class($this)));
    }
    /**
     * @return null
     */
    protected function getGuard() {
        return property_exists($this, 'guard') ? $this->guard : null;
    }
}