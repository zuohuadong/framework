<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-29 22:44
 */
namespace Notadd\Admin\Controllers;
use Notadd\Foundation\Abstracts\AbstractAdminController;
use Notadd\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Notadd\Foundation\Auth\Models\User;
use Notadd\Foundation\Auth\ThrottlesLogins;
/**
 * Class AuthController
 * @package Notadd\Admin\Controllers
 */
class AuthController extends AbstractAdminController {
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    /**
     * @var array
     */
    protected $middleware = [
        'guest.admin' => [
            'except' => [
                'getLogout',
                'getPassword'
            ]
        ]
    ];
    /**
     * @var string
     */
    protected $loginPath = 'admin/login';
    /**
     * @var string
     */
    protected $redirectAfterLogout = 'admin';
    /**
     * @var string
     */
    protected $redirectPath = 'admin';
    /**
     * @param array $data
     * @return static
     */
    protected function create(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function getLogin() {
        return $this->view('auth.login');
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function getPassword() {
        return $this->view('auth.password');
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function getRegister() {
        return $this->view('auth.register');
    }
    /**
     * @param array $data
     * @return \Illuminate\Validation\Validator
     */
    protected function validator(array $data) {
        return $this->app->make('validator')->make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }
}