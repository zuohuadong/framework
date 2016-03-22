<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-18 16:28
 */
namespace Notadd\Foundation\Auth;
use Illuminate\Http\Request;
/**
 * Class RegistersUsers
 * @package Notadd\Foundation\Auth
 */
trait RegistersUsers {
    use RedirectsUsers;
    /**
     * @return \Illuminate\Http\Response
     */
    public function getRegister() {
        return view('auth.register');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request) {
        $validator = $this->validator($request->all());
        if($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
        $this->app->make('auth')->guard($this->getGuard())->login($this->create($request->all()));
        return redirect($this->redirectPath());
    }
    /**
     * @return null
     */
    protected function getGuard() {
        return property_exists($this, 'guard') ? $this->guard : null;
    }
}