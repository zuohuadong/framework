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
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Notadd\Foundation\Auth\GenericUser;
/**
 * Class DatabaseUserProvider
 * @package Notadd\Foundation\Auth\Providers
 */
class DatabaseUserProvider implements UserProvider {
    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $conn;
    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;
    /**
     * @var string
     */
    protected $table;
    /**
     * DatabaseUserProvider constructor.
     * @param \Illuminate\Database\ConnectionInterface $conn
     * @param \Illuminate\Contracts\Hashing\Hasher $hasher
     * @param $table
     */
    public function __construct(ConnectionInterface $conn, HasherContract $hasher, $table) {
        $this->conn = $conn;
        $this->table = $table;
        $this->hasher = $hasher;
    }
    /**
     * @param mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier) {
        $user = $this->conn->table($this->table)->find($identifier);
        return $this->getGenericUser($user);
    }
    /**
     * @param mixed $identifier
     * @param string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token) {
        $user = $this->conn->table($this->table)->where('id', $identifier)->where('remember_token', $token)->first();
        return $this->getGenericUser($user);
    }
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string $token
     * @return void
     */
    public function updateRememberToken(UserContract $user, $token) {
        $this->conn->table($this->table)->where('id', $user->getAuthIdentifier())->update(['remember_token' => $token]);
    }
    /**
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials) {
        $query = $this->conn->table($this->table);
        foreach($credentials as $key => $value) {
            if(!Str::contains($key, 'password')) {
                $query->where($key, $value);
            }
        }
        $user = $query->first();
        return $this->getGenericUser($user);
    }
    /**
     * @param mixed $user
     * @return \Notadd\Foundation\Auth\GenericUser
     */
    protected function getGenericUser($user) {
        if($user !== null) {
            return new GenericUser((array)$user);
        }
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
}