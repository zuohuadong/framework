<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 10:46
 */
namespace Notadd\Foundation\Database\Migrations;
use Illuminate\Database\ConnectionInterface;
/**
 * Class Migration
 * @package Notadd\Foundation\Database\Migrations
 */
abstract class Migration {
    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $connection;
    /**
     * @var \Illuminate\Database\Schema\Builder
     */
    protected $schema;
    /**
     * Migration constructor.
     * @param \Illuminate\Database\ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection) {
        $this->connection = $connection;
        $this->schema = call_user_func([$connection, 'getSchemaBuilder']);
    }
    /**
     * @return void
     */
    abstract public function down();
    /**
     * @return void
     */
    abstract public function up();
}