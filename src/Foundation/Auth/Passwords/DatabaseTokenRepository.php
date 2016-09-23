<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:21
 */
namespace Notadd\Foundation\Auth\Passwords;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Notadd\Foundation\Auth\Contracts\TokenRepository as TokenRepositoryContract;
/**
 * Class DatabaseTokenRepository
 * @package Notadd\Foundation\Auth\Passwords
 */
class DatabaseTokenRepository implements TokenRepositoryContract {
    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $connection;
    /**
     * @var string
     */
    protected $table;
    /**
     * @var string
     */
    protected $hashKey;
    /**
     * @var int
     */
    protected $expires;
    /**
     * DatabaseTokenRepository constructor.
     * @param \Illuminate\Database\ConnectionInterface $connection
     * @param string $table
     * @param string $hashKey
     * @param int $expires
     */
    public function __construct(ConnectionInterface $connection, $table, $hashKey, $expires = 60) {
        $this->table = $table;
        $this->hashKey = $hashKey;
        $this->expires = $expires * 60;
        $this->connection = $connection;
    }
    /**
     * @param \Illuminate\Contracts\Auth\CanResetPassword $user
     * @return string
     */
    public function create(CanResetPasswordContract $user) {
        $email = $user->getEmailForPasswordReset();
        $this->deleteExisting($user);
        $token = $this->createNewToken();
        $this->getTable()->insert($this->getPayload($email, $token));
        return $token;
    }
    /**
     * @param \Illuminate\Contracts\Auth\CanResetPassword $user
     * @return int
     */
    protected function deleteExisting(CanResetPasswordContract $user) {
        return $this->getTable()->where('email', $user->getEmailForPasswordReset())->delete();
    }
    /**
     * @param string $email
     * @param string $token
     * @return array
     */
    protected function getPayload($email, $token) {
        return [
            'email' => $email,
            'token' => $token,
            'created_at' => new Carbon
        ];
    }
    /**
     * @param \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param string $token
     * @return bool
     */
    public function exists(CanResetPasswordContract $user, $token) {
        $email = $user->getEmailForPasswordReset();
        $token = (array)$this->getTable()->where('email', $email)->where('token', $token)->first();
        return $token && !$this->tokenExpired($token);
    }
    /**
     * @param array $token
     * @return bool
     */
    protected function tokenExpired($token) {
        $expiresAt = Carbon::parse($token['created_at'])->addSeconds($this->expires);
        return $expiresAt->isPast();
    }
    /**
     * @param string $token
     * @return void
     */
    public function delete($token) {
        $this->getTable()->where('token', $token)->delete();
    }
    /**
     * @return void
     */
    public function deleteExpired() {
        $expiredAt = Carbon::now()->subSeconds($this->expires);
        $this->getTable()->where('created_at', '<', $expiredAt)->delete();
    }
    /**
     * @return string
     */
    public function createNewToken() {
        return hash_hmac('sha256', Str::random(40), $this->hashKey);
    }
    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getTable() {
        return $this->connection->table($this->table);
    }
    /**
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function getConnection() {
        return $this->connection;
    }
}