<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:01
 */
namespace Notadd\Foundation\Auth\Guards;
use Illuminate\Contracts\Auth\Guard;
use Notadd\Foundation\Auth\Traits\GuardHelpers;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Class RequestGuard
 * @package Notadd\Foundation\Auth
 */
class RequestGuard implements Guard {
    use GuardHelpers;
    /**
     * @var callable
     */
    protected $callback;
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;
    /**
     * RequestGuard constructor.
     * @param callable $callback
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(callable $callback, Request $request) {
        $this->request = $request;
        $this->callback = $callback;
    }
    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user() {
        if(!is_null($this->user)) {
            return $this->user;
        }
        return $this->user = call_user_func($this->callback, $this->request);
    }
    /**
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = []) {
        return !is_null((new static($this->callback, $credentials['request']))->user());
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return $this
     */
    public function setRequest(Request $request) {
        $this->request = $request;
        return $this;
    }
}