<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 11:40
 */
namespace Notadd\Foundation\Database\Migrations;
use Carbon\Carbon;
use Illuminate\Database\Migrations\MigrationCreator as IlluminateMigrationCreator;
/**
 * Class MigrationCreator
 * @package Notadd\Foundation\Database\Migrations
 */
class MigrationCreator extends IlluminateMigrationCreator {
    /**
     * @return string
     */
    public function getStubPath() {
        return realpath(__DIR__ . '/../../../../stubs/migrations');
    }
    /**
     * @param string $name
     * @param string $stub
     * @param string $table
     * @return string
     */
    public function populateStub($name, $stub, $table) {
        $stub = parent::populateStub($name, $stub, $table);
        $stub = str_replace('DummyDatetime', Carbon::now()->toDateTimeString(), $stub);
        return $stub;
    }
}