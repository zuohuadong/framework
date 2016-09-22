<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-29 18:07
 */
namespace Notadd\Foundation\Http\Pipelines;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zend\Stratigility\MiddlewareInterface;
/**
 * Class SessionStarter
 * @package Notadd\Foundation\Http\Middlewares
 */
class SessionStarter implements MiddlewareInterface {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable|null $out
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $out = null) {
        $session = $this->startSession();
        $request = $request->withAttribute('session', $session);
        $response = $out ? $out($request, $response) : $response;
        $response = $this->withCsrfTokenHeader($response, $session);
        return $this->withSessionCookie($response, $session);
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    private function startSession() {
        $session = new Session;
        $session->setName('notadd_session');
        $session->start();
        if(!$session->has('csrf_token')) {
            $session->set('csrf_token', Str::random(40));
        }
        return $session;
    }
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function withCsrfTokenHeader(Response $response, SessionInterface $session) {
        if($session->has('csrf_token')) {
            $response = $response->withHeader('X-CSRF-Token', $session->get('csrf_token'));
        }
        return $response;
    }
    private function withSessionCookie(Response $response, SessionInterface $session) {
        return FigResponseCookies::set($response, SetCookie::create($session->getName(), $session->getId())->withPath('/')->withHttpOnly(true));
    }
}