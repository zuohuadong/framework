<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 15:54
 */
namespace Notadd\Foundation\Auth\Guards;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\SupportsBasicAuth;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Cookie\QueueingFactory as CookieJar;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Str;
use Notadd\Foundation\Auth\Events;
use Notadd\Foundation\Auth\Events\Attempting;
use Notadd\Foundation\Auth\Events\Authenticated;
use Notadd\Foundation\Auth\Events\Failed;
use Notadd\Foundation\Auth\Events\Login;
use Notadd\Foundation\Auth\Traits\GuardHelpers;
use Psr\Http\Message\ServerRequestInterface as Request;
use RuntimeException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zend\Diactoros\Response;
/**
 * Class SessionGuard
 * @package Notadd\Foundation\Auth
 */
class SessionGuard implements StatefulGuard, SupportsBasicAuth {
    use GuardHelpers;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $lastAttempted;
    /**
     * @var bool
     */
    protected $viaRemember = false;
    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;
    /**
     * @var \Illuminate\Contracts\Cookie\QueueingFactory
     */
    protected $cookie;
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;
    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;
    /**
     * @var bool
     */
    protected $loggedOut = false;
    /**
     * @var bool
     */
    protected $tokenRetrievalAttempted = false;
    /**
     * SessionGuard constructor.
     * @param string $name
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param \Psr\Http\Message\ServerRequestInterface|null $request
     */
    public function __construct($name, UserProvider $provider, SessionInterface $session, Request $request = null) {
        $this->name = $name;
        $this->session = $session;
        $this->request = $request;
        $this->provider = $provider;
    }
    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user() {
        if($this->loggedOut) {
            return null;
        }
        if(!is_null($this->user)) {
            return $this->user;
        }
        $id = $this->session->get($this->getName());
        $user = null;
        if(!is_null($id)) {
            if($user = $this->provider->retrieveById($id)) {
                $this->fireAuthenticatedEvent($user);
            }
        }
        $recaller = $this->getRecaller();
        if(is_null($user) && !is_null($recaller)) {
            $user = $this->getUserByRecaller($recaller);
            if($user) {
                $this->updateSession($user->getAuthIdentifier());
                $this->fireLoginEvent($user, true);
            }
        }
        return $this->user = $user;
    }
    /**
     * @return int|null
     */
    public function id() {
        if($this->loggedOut) {
            return null;
        }
        $id = $this->session->get($this->getName());
        if(is_null($id) && $this->user()) {
            $id = $this->user()->getAuthIdentifier();
        }
        return $id;
    }
    /**
     * @param string $recaller
     * @return mixed
     */
    protected function getUserByRecaller($recaller) {
        if($this->validRecaller($recaller) && !$this->tokenRetrievalAttempted) {
            $this->tokenRetrievalAttempted = true;
            list($id, $token) = explode('|', $recaller, 2);
            $this->viaRemember = !is_null($user = $this->provider->retrieveByToken($id, $token));
            return $user;
        }
    }
    /**
     * @return string|null
     */
    protected function getRecaller() {
        return $this->request->cookies->get($this->getRecallerName());
    }
    /**
     * @return string|null
     */
    protected function getRecallerId() {
        if($this->validRecaller($recaller = $this->getRecaller())) {
            return head(explode('|', $recaller));
        }
    }
    /**
     * @param mixed $recaller
     * @return bool
     */
    protected function validRecaller($recaller) {
        if(!is_string($recaller) || !Str::contains($recaller, '|')) {
            return false;
        }
        $segments = explode('|', $recaller);
        return count($segments) == 2 && trim($segments[0]) !== '' && trim($segments[1]) !== '';
    }
    /**
     * @param array $credentials
     * @return bool
     */
    public function once(array $credentials = []) {
        if($this->validate($credentials)) {
            $this->setUser($this->lastAttempted);
            return true;
        }
        return false;
    }
    /**
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = []) {
        return $this->attempt($credentials, false, false);
    }
    /**
     * @param string $field
     * @param array $extraConditions
     * @return \Zend\Diactoros\Response
     */
    public function basic($field = 'email', $extraConditions = []) {
        if($this->check()) {
            return null;
        }
        if($this->attemptBasic($this->getRequest(), $field, $extraConditions)) {
            return null;
        }
        return $this->getBasicResponse();
    }
    /**
     * @param string $field
     * @param array $extraConditions
     * @return \Zend\Diactoros\Response
     */
    public function onceBasic($field = 'email', $extraConditions = []) {
        $credentials = $this->getBasicCredentials($this->getRequest(), $field);
        if(!$this->once(array_merge($credentials, $extraConditions))) {
            return $this->getBasicResponse();
        }
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param $field
     * @param array $extraConditions
     * @return bool
     */
    protected function attemptBasic(Request $request, $field, $extraConditions = []) {
        if(!$request->getHeader('PHP_AUTH_USER')) {
            return false;
        }
        $credentials = $this->getBasicCredentials($request, $field);
        return $this->attempt(array_merge($credentials, $extraConditions));
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param $field
     * @return array
     */
    protected function getBasicCredentials(Request $request, $field) {
        return [
            $field => $request->getHeader('PHP_AUTH_USER'),
            'password' => $request->getHeader('PHP_AUTH_PW')
        ];
    }
    /**
     * @return \Zend\Diactoros\Response
     */
    protected function getBasicResponse() {
        $headers = ['WWW-Authenticate' => 'Basic'];
        return new Response('Invalid credentials.', 401, $headers);
    }
    /**
     * @param array $credentials
     * @param bool $remember
     * @param bool $login
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false, $login = true) {
        $this->fireAttemptEvent($credentials, $remember, $login);
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);
        if($this->hasValidCredentials($user, $credentials)) {
            if($login) {
                $this->login($user, $remember);
            }
            return true;
        }
        if($login) {
            $this->fireFailedEvent($user, $credentials);
        }
        return false;
    }
    /**
     * @param mixed $user
     * @param array $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials) {
        return !is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }
    /**
     * @param array $credentials
     * @param bool $remember
     * @param bool $login
     * @return void
     */
    protected function fireAttemptEvent(array $credentials, $remember, $login) {
        if(isset($this->events)) {
            $this->events->fire(new Attempting($credentials, $remember, $login));
        }
    }
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @param array $credentials
     * @return void
     */
    protected function fireFailedEvent($user, array $credentials) {
        if(isset($this->events)) {
            $this->events->fire(new Failed($user, $credentials));
        }
    }
    /**
     * @param mixed $callback
     * @return void
     */
    public function attempting($callback) {
        if(isset($this->events)) {
            $this->events->listen(Attempting::class, $callback);
        }
    }
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param bool $remember
     * @return void
     */
    public function login(AuthenticatableContract $user, $remember = false) {
        $this->updateSession($user->getAuthIdentifier());
        if($remember) {
            $this->createRememberTokenIfDoesntExist($user);
            $this->queueRecallerCookie($user);
        }
        $this->fireLoginEvent($user, $remember);
        $this->setUser($user);
    }
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param bool $remember
     * @return void
     */
    protected function fireLoginEvent($user, $remember = false) {
        if(isset($this->events)) {
            $this->events->fire(new Login($user, $remember));
        }
    }
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return void
     */
    protected function fireAuthenticatedEvent($user) {
        if(isset($this->events)) {
            $this->events->fire(new Authenticated($user));
        }
    }
    /**
     * @param string $id
     * @return void
     */
    protected function updateSession($id) {
        $this->session->set($this->getName(), $id);
        $this->session->migrate(true);
    }
    /**
     * @param mixed $id
     * @param bool $remember
     * @return \Illuminate\Contracts\Auth\Authenticatable|false
     */
    public function loginUsingId($id, $remember = false) {
        $user = $this->provider->retrieveById($id);
        if(!is_null($user)) {
            $this->login($user, $remember);
            return $user;
        }
        return false;
    }
    /**
     * @param mixed $id
     * @return \Illuminate\Contracts\Auth\Authenticatable|false
     */
    public function onceUsingId($id) {
        $user = $this->provider->retrieveById($id);
        if(!is_null($user)) {
            $this->setUser($user);
            return $user;
        }
        return false;
    }
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return void
     */
    protected function queueRecallerCookie(AuthenticatableContract $user) {
        $value = $user->getAuthIdentifier() . '|' . $user->getRememberToken();
        $this->getCookieJar()->queue($this->createRecaller($value));
    }
    /**
     * @param string $value
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    protected function createRecaller($value) {
        return $this->getCookieJar()->forever($this->getRecallerName(), $value);
    }
    /**
     * @return void
     */
    public function logout() {
        $user = $this->user();
        $this->clearUserDataFromStorage();
        if(!is_null($this->user)) {
            $this->refreshRememberToken($user);
        }
        if(isset($this->events)) {
            $this->events->fire(new Events\Logout($user));
        }
        $this->user = null;
        $this->loggedOut = true;
    }
    /**
     * @return void
     */
    protected function clearUserDataFromStorage() {
        $this->session->remove($this->getName());
        if(!is_null($this->getRecaller())) {
            $recaller = $this->getRecallerName();
            $this->getCookieJar()->queue($this->getCookieJar()->forget($recaller));
        }
    }
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return void
     */
    protected function refreshRememberToken(AuthenticatableContract $user) {
        $user->setRememberToken($token = Str::random(60));
        $this->provider->updateRememberToken($user, $token);
    }
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return void
     */
    protected function createRememberTokenIfDoesntExist(AuthenticatableContract $user) {
        if(empty($user->getRememberToken())) {
            $this->refreshRememberToken($user);
        }
    }
    /**
     * @return \Illuminate\Contracts\Cookie\QueueingFactory
     * @throws \RuntimeException
     */
    public function getCookieJar() {
        if(!isset($this->cookie)) {
            throw new RuntimeException('Cookie jar has not been set.');
        }
        return $this->cookie;
    }
    /**
     * @param \Illuminate\Contracts\Cookie\QueueingFactory $cookie
     * @return void
     */
    public function setCookieJar(CookieJar $cookie) {
        $this->cookie = $cookie;
    }
    /**
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public function getDispatcher() {
        return $this->events;
    }
    /**
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    public function setDispatcher(Dispatcher $events) {
        $this->events = $events;
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    public function getSession() {
        return $this->session;
    }
    /**
     * @return \Illuminate\Contracts\Auth\UserProvider
     */
    public function getProvider() {
        return $this->provider;
    }
    /**
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     * @return void
     */
    public function setProvider(UserProvider $provider) {
        $this->provider = $provider;
    }
    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUser() {
        return $this->user;
    }
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return $this
     */
    public function setUser(AuthenticatableContract $user) {
        $this->user = $user;
        $this->loggedOut = false;
        $this->fireAuthenticatedEvent($user);
        return $this;
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest() {
        return $this->request;
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return $this
     */
    public function setRequest(Request $request) {
        $this->request = $request;
        return $this;
    }
    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function getLastAttempted() {
        return $this->lastAttempted;
    }
    /**
     * @return string
     */
    public function getName() {
        return 'login_' . $this->name . '_' . sha1(static::class);
    }
    /**
     * @return string
     */
    public function getRecallerName() {
        return 'remember_' . $this->name . '_' . sha1(static::class);
    }
    /**
     * @return bool
     */
    public function viaRemember() {
        return $this->viaRemember;
    }
}