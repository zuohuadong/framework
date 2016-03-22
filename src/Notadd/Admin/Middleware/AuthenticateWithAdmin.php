<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-18 16:28
 */
namespace Notadd\Admin\Middleware;
use Closure;
use Illuminate\Auth\AuthManager;
/**
 * Class AuthenticateWithAdmin
 * @package Notadd\Admin\Middleware
 */
class AuthenticateWithAdmin {
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;
    /**
     * AuthenticateWithAdmin constructor.
     * @param \Illuminate\Auth\AuthManager $auth
     */
    public function __construct(AuthManager $auth) {
        $this->auth = $auth;
    }
    /**
     * @param $request
     * @param \Closure $next
     * @param null $guard
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next, $guard = null) {
        if($this->auth->guard($guard)->guest()) {
            if($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('admin/login');
            }
        }
        return $next($request);
    }
}