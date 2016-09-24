<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-24 17:27
 */
namespace Notadd\Member;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Notadd\Member\Listeners\RouteRegister;
/**
 * Class MemberServiceProvider
 * @package Notadd\Member
 */
class MemberServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->events->subscribe(RouteRegister::class);
    }
}