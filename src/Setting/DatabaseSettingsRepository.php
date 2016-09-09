<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-31 16:16
 */
namespace Notadd\Setting;
use Illuminate\Database\ConnectionInterface;
use Notadd\Setting\Contracts\SettingsRepository as SettingsRepositoryContract;
/**
 * Class DatabaseSettingsRepository
 * @package Notadd\Setting
 */
class DatabaseSettingsRepository implements SettingsRepositoryContract {
    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $database;
    /**
     * DatabaseSettingsRepository constructor.
     * @param \Illuminate\Database\ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection) {
        $this->database = $connection;
    }
    /**
     * @return mixed
     */
    public function all() {
        return $this->database->table('settings')->pluck('value', 'key');
    }
    /**
     * @param $keyLike
     */
    public function delete($keyLike) {
        $this->database->table('settings')->where('key', 'like', $keyLike)->delete();
    }
    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null) {
        if(is_null($value = $this->database->table('settings')->where('key', $key)->value('value'))) {
            return $default;
        }
        return $value;
    }
    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value) {
        $query = $this->database->table('settings')->where('key', $key);
        $method = $query->exists() ? 'update' : 'insert';
        $query->$method(compact('key', 'value'));
    }
}