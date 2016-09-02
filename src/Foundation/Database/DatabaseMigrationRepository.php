<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-31 20:28
 */
namespace Notadd\Foundation\Database;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Schema\Blueprint;
use Notadd\Foundation\Database\Contracts\MigrationRepository;
/**
 * Class DatabaseMigrationRepository
 * @package Notadd\Foundation\Database
 */
class DatabaseMigrationRepository implements MigrationRepository {
    /**
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $resolver;
    /**
     * @var string
     */
    protected $table;
    /**
     * @var string
     */
    protected $connection;
    /**
     * DatabaseMigrationRepository constructor.
     * @param \Illuminate\Database\ConnectionResolverInterface $resolver
     * @param $table
     */
    public function __construct(Resolver $resolver, $table) {
        $this->table = $table;
        $this->resolver = $resolver;
    }
    /**
     * @param null $extension
     * @return array
     */
    public function getRan($extension = null) {
        return $this->table()->where('extension', $extension)->orderBy('migration', 'asc')->lists('migration');
    }
    /**
     * Log that a migration was run.
     * @param string $file
     * @param string $extension
     * @return void
     */
    public function log($file, $extension = null) {
        $record = [
            'migration' => $file,
            'extension' => $extension
        ];
        $this->table()->insert($record);
    }
    /**
     * @param string $file
     * @param string $extension
     * @return void
     */
    public function delete($file, $extension = null) {
        $query = $this->table()->where('migration', $file);
        if(is_null($extension)) {
            $query->whereNull('extension');
        } else {
            $query->where('extension', $extension);
        }
        $query->delete();
    }
    /**
     * @return void
     */
    public function createRepository() {
        $schema = $this->getConnection()->getSchemaBuilder();
        $schema->create($this->table, function (Blueprint $table) {
            $table->string('migration');
            $table->string('extension')->nullable();
        });
    }
    /**
     * @return bool
     */
    public function repositoryExists() {
        $schema = $this->getConnection()->getSchemaBuilder();
        return $schema->hasTable($this->table);
    }
    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table() {
        return $this->getConnection()->table($this->table);
    }
    /**
     * @return \Illuminate\Database\ConnectionResolverInterface
     */
    public function getConnectionResolver() {
        return $this->resolver;
    }
    /**
     * @return \Illuminate\Database\Connection
     */
    public function getConnection() {
        return $this->resolver->connection($this->connection);
    }
    /**
     * @param  string $name
     * @return void
     */
    public function setSource($name) {
        $this->connection = $name;
    }
}