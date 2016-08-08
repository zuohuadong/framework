<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-20 18:44
 */
namespace Notadd\Admin\Controllers;
use Illuminate\Container\Container;
use Notadd\Admin\Requests\PasswordRequest;
use Notadd\Foundation\Abstracts\AbstractAdminController;
/**
 * Class PasswordController
 * @package Notadd\Admin\Controllers
 */
class PasswordController extends AbstractAdminController {
    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;
    /**
     * PasswordController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->hasher = Container::getInstance()->make('hash');
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        return $this->view('admin::auth.password');
    }
    /**
     * @param \Notadd\Admin\Requests\PasswordRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(PasswordRequest $request) {
        if(!$this->hasher->check($request->offsetGet('oldpassword'), $this->user->getAttribute('password'))) {
            return $this->redirect->back()->withErrors("旧密码验证错误！");
        }
        $data = [];
        $data['password'] = bcrypt($request->offsetGet('password'));
        $this->user->update($data);
        return $this->redirect->to('admin');
    }
}