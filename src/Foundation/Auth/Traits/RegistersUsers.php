<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-26 10:45
 */
namespace Notadd\Foundation\Auth\Traits;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Class RegistersUsers
 * @package Notadd\Foundation\Auth\Traits
 */
trait RegistersUsers {
    use RedirectsUsers;
    /**
     * @return mixed
     */
    public function showRegistrationForm() {
        return view('auth.register');
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return mixed
     */
    public function register(Request $request) {
        $this->validator($request->getAttributes())->validate();
        event(new Registered($user = $this->create($request->all())));
        $this->guard()->login($user);
        return redirect($this->redirectPath());
    }
    /**
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard() {
        return $this->auth->guard();
    }
}