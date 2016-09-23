<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:26
 */
namespace Notadd\Foundation\Auth\Passwords;
use Closure;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Arr;
use Notadd\Foundation\Auth\Contracts\TokenRepository as TokenRepositoryContract;
use UnexpectedValueException;
/**
 * Class PasswordBroker
 * @package Notadd\Foundation\Auth\Passwords
 */
class PasswordBroker implements PasswordBrokerContract {
    /**
     * @var \Notadd\Foundation\Auth\Contracts\TokenRepository
     */
    protected $tokens;
    /**
     * @var \Illuminate\Contracts\Auth\UserProvider
     */
    protected $users;
    /**
     * @var \Closure
     */
    protected $passwordValidator;
    /**
     * PasswordBroker constructor.
     * @param \Notadd\Foundation\Auth\Contracts\TokenRepository $tokens
     * @param \Illuminate\Contracts\Auth\UserProvider $users
     */
    public function __construct(TokenRepositoryContract $tokens, UserProvider $users) {
        $this->users = $users;
        $this->tokens = $tokens;
    }
    /**
     * @param array $credentials
     * @return string
     */
    public function sendResetLink(array $credentials) {
        $user = $this->getUser($credentials);
        if(is_null($user)) {
            return static::INVALID_USER;
        }
        $user->sendPasswordResetNotification($this->tokens->create($user));
        return static::RESET_LINK_SENT;
    }
    /**
     * @param array $credentials
     * @param \Closure $callback
     * @return mixed
     */
    public function reset(array $credentials, Closure $callback) {
        $user = $this->validateReset($credentials);
        if(!$user instanceof CanResetPasswordContract) {
            return $user;
        }
        $pass = $credentials['password'];
        $callback($user, $pass);
        $this->tokens->delete($credentials['token']);
        return static::PASSWORD_RESET;
    }
    /**
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword
     */
    protected function validateReset(array $credentials) {
        if(is_null($user = $this->getUser($credentials))) {
            return static::INVALID_USER;
        }
        if(!$this->validateNewPassword($credentials)) {
            return static::INVALID_PASSWORD;
        }
        if(!$this->tokens->exists($user, $credentials['token'])) {
            return static::INVALID_TOKEN;
        }
        return $user;
    }
    /**
     * @param \Closure $callback
     * @return void
     */
    public function validator(Closure $callback) {
        $this->passwordValidator = $callback;
    }
    /**
     * @param array $credentials
     * @return bool
     */
    public function validateNewPassword(array $credentials) {
        list($password, $confirm) = [
            $credentials['password'],
            $credentials['password_confirmation'],
        ];
        if(isset($this->passwordValidator)) {
            return call_user_func($this->passwordValidator, $credentials) && $password === $confirm;
        }
        return $this->validatePasswordWithDefaults($credentials);
    }
    /**
     * @param array $credentials
     * @return bool
     */
    protected function validatePasswordWithDefaults(array $credentials) {
        list($password, $confirm) = [
            $credentials['password'],
            $credentials['password_confirmation'],
        ];
        return $password === $confirm && mb_strlen($password) >= 6;
    }
    /**
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword
     * @throws \UnexpectedValueException
     */
    public function getUser(array $credentials) {
        $credentials = Arr::except($credentials, ['token']);
        $user = $this->users->retrieveByCredentials($credentials);
        if($user && !$user instanceof CanResetPasswordContract) {
            throw new UnexpectedValueException('User must implement CanResetPassword interface.');
        }
        return $user;
    }
    /**
     * @param CanResetPasswordContract $user
     * @return string
     */
    public function createToken(CanResetPasswordContract $user) {
        return $this->tokens->create($user);
    }
    /**
     * @param string $token
     * @return void
     */
    public function deleteToken($token) {
        $this->tokens->delete($token);
    }
    /**
     * @param CanResetPasswordContract $user
     * @param string $token
     * @return bool
     */
    public function tokenExists(CanResetPasswordContract $user, $token) {
        return $this->tokens->exists($user, $token);
    }
    /**
     * @return \Notadd\Foundation\Auth\Contracts\TokenRepository
     */
    public function getRepository() {
        return $this->tokens;
    }
}