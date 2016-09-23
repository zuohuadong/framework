<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:17
 */
namespace Notadd\Foundation\Auth\Access;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Notadd\Foundation\Auth\Exceptions\AuthorizationException;
use Notadd\Foundation\Auth\Traits\HandlesAuthorization;
/**
 * Class Gate
 * @package Notadd\Foundation\Auth\Access
 */
class Gate {
    use HandlesAuthorization;
    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;
    /**
     * @var callable
     */
    protected $userResolver;
    /**
     * @var array
     */
    protected $abilities = [];
    /**
     * @var array
     */
    protected $policies = [];
    /**
     * @var array
     */
    protected $beforeCallbacks = [];
    /**
     * @var array
     */
    protected $afterCallbacks = [];
    /**
     * Gate constructor.
     * @param \Illuminate\Contracts\Container\Container $container
     * @param callable $userResolver
     * @param array $abilities
     * @param array $policies
     * @param array $beforeCallbacks
     * @param array $afterCallbacks
     */
    public function __construct(Container $container, callable $userResolver, array $abilities = [], array $policies = [], array $beforeCallbacks = [], array $afterCallbacks = []) {
        $this->policies = $policies;
        $this->container = $container;
        $this->abilities = $abilities;
        $this->userResolver = $userResolver;
        $this->afterCallbacks = $afterCallbacks;
        $this->beforeCallbacks = $beforeCallbacks;
    }
    /**
     * @param $ability
     * @return bool
     */
    public function has($ability) {
        return isset($this->abilities[$ability]);
    }
    /**
     * @param $ability
     * @param $callback
     * @return $this
     */
    public function define($ability, $callback) {
        if(is_callable($callback)) {
            $this->abilities[$ability] = $callback;
        } elseif(is_string($callback) && Str::contains($callback, '@')) {
            $this->abilities[$ability] = $this->buildAbilityCallback($callback);
        } else {
            throw new InvalidArgumentException("Callback must be a callable or a 'Class@method' string.");
        }
        return $this;
    }
    /**
     * @param $callback
     * @return \Closure
     */
    protected function buildAbilityCallback($callback) {
        return function () use ($callback) {
            list($class, $method) = explode('@', $callback);
            return $this->resolvePolicy($class)->{$method}(...func_get_args());
        };
    }
    /**
     * @param $class
     * @param $policy
     * @return $this
     */
    public function policy($class, $policy) {
        $this->policies[$class] = $policy;
        return $this;
    }
    /**
     * @param callable $callback
     * @return $this
     */
    public function before(callable $callback) {
        $this->beforeCallbacks[] = $callback;
        return $this;
    }
    /**
     * @param callable $callback
     * @return $this
     */
    public function after(callable $callback) {
        $this->afterCallbacks[] = $callback;
        return $this;
    }
    /**
     * @param $ability
     * @param array $arguments
     * @return bool
     */
    public function allows($ability, $arguments = []) {
        return $this->check($ability, $arguments);
    }
    /**
     * @param $ability
     * @param array $arguments
     * @return bool
     */
    public function denies($ability, $arguments = []) {
        return !$this->allows($ability, $arguments);
    }
    /**
     * @param $ability
     * @param array $arguments
     * @return bool
     */
    public function check($ability, $arguments = []) {
        try {
            $result = $this->raw($ability, $arguments);
        } catch(AuthorizationException $e) {
            return false;
        }
        return (bool)$result;
    }
    /**
     * @param $ability
     * @param array $arguments
     * @return mixed|\Notadd\Foundation\Auth\Access\Response|void
     */
    public function authorize($ability, $arguments = []) {
        $result = $this->raw($ability, $arguments);
        if($result instanceof Response) {
            return $result;
        }
        return $result ? $this->allow() : $this->deny();
    }
    /**
     * @param $ability
     * @param array $arguments
     * @return bool|null
     */
    protected function raw($ability, $arguments = []) {
        if(!$user = $this->resolveUser()) {
            return false;
        }
        $arguments = is_array($arguments) ? $arguments : [$arguments];
        if(is_null($result = $this->callBeforeCallbacks($user, $ability, $arguments))) {
            $result = $this->callAuthCallback($user, $ability, $arguments);
        }
        $this->callAfterCallbacks($user, $ability, $arguments, $result);
        return $result;
    }
    /**
     * @param $user
     * @param $ability
     * @param array $arguments
     * @return mixed
     */
    protected function callAuthCallback($user, $ability, array $arguments) {
        $callback = $this->resolveAuthCallback($user, $ability, $arguments);
        return $callback($user, ...$arguments);
    }
    /**
     * @param $user
     * @param $ability
     * @param array $arguments
     * @return mixed
     */
    protected function callBeforeCallbacks($user, $ability, array $arguments) {
        $arguments = array_merge([
            $user,
            $ability
        ], [$arguments]);
        foreach($this->beforeCallbacks as $before) {
            if(!is_null($result = $before(...$arguments))) {
                return $result;
            }
        }
    }
    /**
     * @param $user
     * @param $ability
     * @param array $arguments
     * @param $result
     */
    protected function callAfterCallbacks($user, $ability, array $arguments, $result) {
        $arguments = array_merge([
            $user,
            $ability,
            $result
        ], [$arguments]);
        foreach($this->afterCallbacks as $after) {
            $after(...$arguments);
        }
    }
    /**
     * @param $user
     * @param $ability
     * @param array $arguments
     * @return callable|\Closure|mixed
     */
    protected function resolveAuthCallback($user, $ability, array $arguments) {
        if($this->firstArgumentCorrespondsToPolicy($arguments)) {
            return $this->resolvePolicyCallback($user, $ability, $arguments);
        } elseif(isset($this->abilities[$ability])) {
            return $this->abilities[$ability];
        } else {
            return function () {
                return false;
            };
        }
    }
    /**
     * @param array $arguments
     * @return bool
     */
    protected function firstArgumentCorrespondsToPolicy(array $arguments) {
        if(!isset($arguments[0])) {
            return false;
        }
        if(is_object($arguments[0])) {
            return isset($this->policies[get_class($arguments[0])]);
        }
        return is_string($arguments[0]) && isset($this->policies[$arguments[0]]);
    }
    /**
     * @param $user
     * @param $ability
     * @param array $arguments
     * @return \Closure
     */
    protected function resolvePolicyCallback($user, $ability, array $arguments) {
        return function () use ($user, $ability, $arguments) {
            $instance = $this->getPolicyFor($arguments[0]);
            if(method_exists($instance, 'before')) {
                if(!is_null($result = $instance->before($user, $ability, ...$arguments))) {
                    return $result;
                }
            }
            if(strpos($ability, '-') !== false) {
                $ability = Str::camel($ability);
            }
            if(isset($arguments[0]) && is_string($arguments[0])) {
                array_shift($arguments);
            }
            if(!is_callable([
                $instance,
                $ability
            ])
            ) {
                return false;
            }
            return $instance->{$ability}($user, ...$arguments);
        };
    }
    /**
     * @param $class
     * @return mixed
     */
    public function getPolicyFor($class) {
        if(is_object($class)) {
            $class = get_class($class);
        }
        if(!isset($this->policies[$class])) {
            throw new InvalidArgumentException("Policy not defined for [{$class}].");
        }
        return $this->resolvePolicy($this->policies[$class]);
    }
    /**
     * @param $class
     * @return mixed
     */
    public function resolvePolicy($class) {
        return $this->container->make($class);
    }
    /**
     * @param $user
     * @return static
     */
    public function forUser($user) {
        $callback = function () use ($user) {
            return $user;
        };
        return new static($this->container, $callback, $this->abilities, $this->policies, $this->beforeCallbacks, $this->afterCallbacks);
    }
    /**
     * @return mixed
     */
    protected function resolveUser() {
        return call_user_func($this->userResolver);
    }
}