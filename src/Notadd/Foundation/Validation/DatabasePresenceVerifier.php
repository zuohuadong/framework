<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-07 16:48
 */
namespace Notadd\Foundation\Validation;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Validation\PresenceVerifierInterface;
/**
 * Class DatabasePresenceVerifier
 * @package Notadd\Foundation\Validation
 */
class DatabasePresenceVerifier implements PresenceVerifierInterface {
    /**
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $db;
    /**
     * @var string
     */
    protected $connection = null;
    /**
     * DatabasePresenceVerifier constructor.
     * @param \Illuminate\Database\ConnectionResolverInterface $db
     */
    public function __construct(ConnectionResolverInterface $db) {
        $this->db = $db;
    }
    /**
     * @param  string $collection
     * @param  string $column
     * @param  string $value
     * @param  int $excludeId
     * @param  string $idColumn
     * @param  array $extra
     * @return int
     */
    public function getCount($collection, $column, $value, $excludeId = null, $idColumn = null, array $extra = []) {
        $query = $this->table($collection)->where($column, '=', $value);
        if(!is_null($excludeId) && $excludeId != 'NULL') {
            $query->where($idColumn ?: 'id', '<>', $excludeId);
        }
        foreach($extra as $key => $extraValue) {
            $this->addWhere($query, $key, $extraValue);
        }
        return $query->count();
    }
    /**
     * @param  string $collection
     * @param  string $column
     * @param  array $values
     * @param  array $extra
     * @return int
     */
    public function getMultiCount($collection, $column, array $values, array $extra = []) {
        $query = $this->table($collection)->whereIn($column, $values);
        foreach($extra as $key => $extraValue) {
            $this->addWhere($query, $key, $extraValue);
        }
        return $query->count();
    }
    /**
     * @param  \Illuminate\Database\Query\Builder $query
     * @param  string $key
     * @param  string $extraValue
     * @return void
     */
    protected function addWhere($query, $key, $extraValue) {
        if($extraValue === 'NULL') {
            $query->whereNull($key);
        } elseif($extraValue === 'NOT_NULL') {
            $query->whereNotNull($key);
        } else {
            $query->where($key, $extraValue);
        }
    }
    /**
     * @param  string $table
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table($table) {
        return $this->db->connection($this->connection)->table($table);
    }
    /**
     * @param  string $connection
     * @return void
     */
    public function setConnection($connection) {
        $this->connection = $connection;
    }
}