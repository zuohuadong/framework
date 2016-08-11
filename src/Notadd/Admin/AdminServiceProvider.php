<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-29 18:19
 */
namespace Notadd\Admin;
use Notadd\Admin\Controllers\AdminController;
use Notadd\Admin\Controllers\AuthController;
use Notadd\Admin\Controllers\PasswordController;
use Notadd\Admin\Listeners\RouteMatched;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
/**
 * Class AdminServiceProvider
 * @package Notadd\Admin
 */
class AdminServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->events->subscribe(RouteMatched::class);
        $this->loadViewsFrom(realpath($this->app->basePath() . '/../template/admin/views'), 'admin');
        $this->router->group(['prefix' => 'admin'], function () {
            $this->router->get('login', AuthController::class . '@getLogin');
            $this->router->post('login', AuthController::class . '@postLogin');
            $this->router->get('logout', AuthController::class . '@getLogout');
            $this->router->get('register', AuthController::class . '@getRegister');
            $this->router->post('register', AuthController::class . '@postRegister');
        });
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->router->get('/', AdminController::class . '@init');
            $this->router->resource('password', PasswordController::class);
        });
    }
}