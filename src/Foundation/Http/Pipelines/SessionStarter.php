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
use Illuminate\Session\CookieSessionHandler;
use Illuminate\Session\SessionInterface;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;
/**
 * Class SessionStarter
 * @package Notadd\Foundation\Http\Middlewares
 */
class SessionStarter implements MiddlewareInterface {
    /**
     * @var \Illuminate\Session\SessionManager
     */
    protected $manager;
    /**
     * @var bool
     */
    protected $sessionHandled = false;
    /**
     * SessionStarter constructor.
     * @param \Illuminate\Session\SessionManager $manager
     */
    public function __construct(SessionManager $manager) {
        $this->manager = $manager;
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable|null $out
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $out = null) {
        $this->sessionHandled = true;
        if($this->sessionConfigured()) {
            $session = $this->startSession($request);
            $request = $request->withAttribute('session', $session);
        }
        $response = $out ? $out($request, $response) : $response;
        if($this->sessionConfigured()) {
            $response = $this->withSessionCookie($response, $session);
        }
        return $response;
    }
    /**
     * @param \Illuminate\Session\SessionInterface $session
     */
    protected function collectGarbage(SessionInterface $session) {
        $config = $this->manager->getSessionConfig();
        if($this->configHitsLottery($config)) {
            $session->getHandler()->gc($this->getSessionLifetimeInSeconds());
        }
    }
    /**
     * @param array $config
     * @return bool
     */
    protected function configHitsLottery(array $config) {
        return random_int(1, $config['lottery'][1]) <= $config['lottery'][0];
    }
    /**
     * @return mixed
     */
    protected function getSessionLifetimeInSeconds() {
        return Arr::get($this->manager->getSessionConfig(), 'lifetime') * 60;
    }
    /**
     * @return bool
     */
    protected function sessionConfigured() {
        return !is_null(Arr::get($this->manager->getSessionConfig(), 'driver'));
    }
    /**
     * @param array|null $config
     * @return bool
     */
    protected function sessionIsPersistent(array $config = null) {
        $config = $config ?: $this->manager->getSessionConfig();
        return !in_array($config['driver'], [
            null,
            'array'
        ]);
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Illuminate\Session\SessionInterface
     */
    private function startSession(Request $request) {
        $session = $this->manager->driver();
        $session->setId(collect($request->getCookieParams())->get($session->getName()));
        $session->setRequestOnHandler($request);
        $session->start();
        return $session;
    }
    /**
     * @return bool
     */
    protected function usingCookieSessions() {
        if(!$this->sessionConfigured()) {
            return false;
        }
        return $this->manager->driver()->getHandler() instanceof CookieSessionHandler;
    }
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Illuminate\Session\SessionInterface $session
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function withSessionCookie(Response $response, SessionInterface $session) {
        return FigResponseCookies::set($response, SetCookie::create($session->getName(), $session->getId())->withPath('/')->withHttpOnly(true));
    }
}