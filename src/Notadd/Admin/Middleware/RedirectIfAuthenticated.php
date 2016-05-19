<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-18 16:28
 */
namespace Notadd\Admin\Middleware;
use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\UrlGenerator;
/**
 * Class RedirectIfAuthenticated
 * @package Notadd\Admin\Middleware
 */
class RedirectIfAuthenticated {
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;
    /**
     * RedirectIfAuthenticated constructor.
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth) {
        $this->auth = $auth;
    }
    /**
     * @param $request
     * @param \Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next) {
        if($this->auth->check()) {
            return new RedirectResponse(Container::getInstance()->make('url')->to('admin'));
        }
        return $next($request);
    }
}