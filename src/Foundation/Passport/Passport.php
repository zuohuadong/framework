<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:30
 */
namespace Notadd\Foundation\Passport;
use DateInterval;
use Carbon\Carbon;
use DateTimeInterface;
/**
 * Class Passport
 * @package Notadd\Foundation\Passport
 */
class Passport {
    /**
     * @var bool
     */
    public static $pruneRevokedTokens = false;
    /**
     * @var int
     */
    public static $personalAccessClient;
    /**
     * @var array
     */
    public static $scopes = [];
    /**
     * @var \DateTimeInterface|null
     */
    public static $tokensExpireAt;
    /**
     * @var \DateTimeInterface|null
     */
    public static $refreshTokensExpireAt;
    /**
     * @var string
     */
    public static $keyPath;
    /**
     * @var bool
     */
    public static $runsMigrations = true;
    /**
     * @param null $callback
     * @param array $options
     */
    public static function routes($callback = null, array $options = []) {
    }
    /**
     * @return static
     */
    public static function pruneRevokedTokens() {
        static::$pruneRevokedTokens = true;
        return new static;
    }
    /**
     * @param int $clientId
     * @return static
     */
    public static function personalAccessClient($clientId) {
        static::$personalAccessClient = $clientId;
        return new static;
    }
    /**
     * @return array
     */
    public static function scopeIds() {
        return static::scopes()->pluck('id')->values()->all();
    }
    /**
     * @param string $id
     * @return bool
     */
    public static function hasScope($id) {
        return $id === '*' || array_key_exists($id, static::$scopes);
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public static function scopes() {
        return collect(static::$scopes)->map(function ($description, $id) {
            return new Scope($id, $description);
        })->values();
    }
    /**
     * @param array $ids
     * @return array
     */
    public static function scopesFor(array $ids) {
        return collect($ids)->map(function ($id) {
            if(isset(static::$scopes[$id])) {
                return new Scope($id, static::$scopes[$id]);
            }
            return;
        })->filter()->values()->all();
    }
    /**
     * @param array $scopes
     * @return void
     */
    public static function tokensCan(array $scopes) {
        static::$scopes = $scopes;
    }
    /**
     * @param \DateTimeInterface|null $date
     * @return \DateInterval|\Notadd\Foundation\Passport\Passport
     */
    public static function tokensExpireIn(DateTimeInterface $date = null) {
        if(is_null($date)) {
            return static::$tokensExpireAt ? Carbon::now()->diff(static::$tokensExpireAt) : new DateInterval('P100Y');
        } else {
            static::$tokensExpireAt = $date;
        }
        return new static;
    }
    /**
     * @param \DateTimeInterface|null $date
     * @return \DateInterval|\Notadd\Foundation\Passport\Passport
     */
    public static function refreshTokensExpireIn(DateTimeInterface $date = null) {
        if(is_null($date)) {
            return static::$refreshTokensExpireAt ? Carbon::now()->diff(static::$refreshTokensExpireAt) : new DateInterval('P100Y');
        } else {
            static::$refreshTokensExpireAt = $date;
        }
        return new static;
    }
    /**
     * @param string $path
     * @return void
     */
    public static function loadKeysFrom($path) {
        static::$keyPath = $path;
    }
    /**
     * @param string $file
     * @return string
     */
    public static function keyPath($file) {
        $file = ltrim($file, "/\\");
        return static::$keyPath ? rtrim(static::$keyPath, "/\\") . DIRECTORY_SEPARATOR . $file : storage_path($file);
    }
    /**
     * @return static
     */
    public static function ignoreMigrations() {
        static::$runsMigrations = false;
        return new static;
    }
}