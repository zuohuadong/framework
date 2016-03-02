<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-18 16:28
 */
namespace Notadd\Foundation\Http\Middleware;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Cookie;
/**
 * Class VerifyCsrfToken
 * @package Notadd\Foundation\Http\Middleware
 */
class VerifyCsrfToken {
    /**
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;
    /**
     * @var array
     */
    protected $except = [
        'admin/theme/cookie',
    ];
    /**
     * @param \Illuminate\Contracts\Encryption\Encrypter $encrypter
     */
    public function __construct(Encrypter $encrypter) {
        $this->encrypter = $encrypter;
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next) {
        if($this->isReading($request) || $this->shouldPassThrough($request) || $this->tokensMatch($request)) {
            return $this->addCookieToResponse($request, $next($request));
        }
        throw new TokenMismatchException;
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function shouldPassThrough($request) {
        foreach($this->except as $except) {
            if($request->is($except)) {
                return true;
            }
        }
        return false;
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function tokensMatch($request) {
        $sessionToken = $request->session()->token();
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
        if(!$token && $header = $request->header('X-XSRF-TOKEN')) {
            $token = $this->encrypter->decrypt($header);
        }
        if(!is_string($sessionToken) || !is_string($token)) {
            return false;
        }
        return hash_equals((string)$request->session()->token(), (string)$token);
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     * @return \Illuminate\Http\Response
     */
    protected function addCookieToResponse($request, $response) {
        $config = config('session');
        $response->headers->setCookie(new Cookie('XSRF-TOKEN', $request->session()->token(), time() + 60 * 120, $config['path'], $config['domain'], false, false));
        return $response;
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function isReading($request) {
        return in_array($request->method(), [
            'HEAD',
            'GET',
            'OPTIONS'
        ]);
    }
}