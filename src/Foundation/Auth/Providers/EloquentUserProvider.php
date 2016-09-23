<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:55
 */
namespace Notadd\Foundation\Auth\Providers;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
/**
 * Class EloquentUserProvider
 * @package Notadd\Foundation\Auth\Providers
 */
class EloquentUserProvider implements UserProvider {
    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;
    /**
     * @var string
     */
    protected $model;
    /**
     * EloquentUserProvider constructor.
     * @param \Illuminate\Contracts\Hashing\Hasher $hasher
     * @param $model
     */
    public function __construct(HasherContract $hasher, $model) {
        $this->model = $model;
        $this->hasher = $hasher;
    }
    /**
     * @param mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier) {
        return $this->createModel()->newQuery()->find($identifier);
    }
    /**
     * @param mixed $identifier
     * @param string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token) {
        $model = $this->createModel();
        return $model->newQuery()->where($model->getAuthIdentifierName(), $identifier)->where($model->getRememberTokenName(), $token)->first();
    }
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string $token
     * @return void
     */
    public function updateRememberToken(UserContract $user, $token) {
        $user->setRememberToken($token);
        $user->save();
    }
    /**
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials) {
        if(empty($credentials)) {
            return null;
        }
        $query = $this->createModel()->newQuery();
        foreach($credentials as $key => $value) {
            if(!Str::contains($key, 'password')) {
                $query->where($key, $value);
            }
        }
        return $query->first();
    }
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials) {
        $plain = $credentials['password'];
        return $this->hasher->check($plain, $user->getAuthPassword());
    }
    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createModel() {
        $class = '\\' . ltrim($this->model, '\\');
        return new $class;
    }
    /**
     * @return \Illuminate\Contracts\Hashing\Hasher
     */
    public function getHasher() {
        return $this->hasher;
    }
    /**
     * @param \Illuminate\Contracts\Hashing\Hasher $hasher
     * @return $this
     */
    public function setHasher(HasherContract $hasher) {
        $this->hasher = $hasher;
        return $this;
    }
    /**
     * @return string
     */
    public function getModel() {
        return $this->model;
    }
    /**
     * @param string $model
     * @return $this
     */
    public function setModel($model) {
        $this->model = $model;
        return $this;
    }
}