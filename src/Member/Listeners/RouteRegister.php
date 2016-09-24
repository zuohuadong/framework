<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-24 17:29
 */
namespace Notadd\Member\Listeners;
use Notadd\Foundation\Routing\Abstracts\AbstractRouteRegister;
use Notadd\Member\Controllers\MemberController;
/**
 * Class RouteRegister
 * @package Notadd\Member\Listeners
 */
class RouteRegister extends AbstractRouteRegister {
    /**
     * @return void
     */
    public function handle() {
        $this->router->group(['middleware' => 'web', 'prefix' => 'member'], function() {
            $this->router->resource('/', MemberController::class, [
                'only' => 'index'
            ]);
        });
    }
}