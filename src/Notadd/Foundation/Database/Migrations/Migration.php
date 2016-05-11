<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-06 19:03
 */
namespace Notadd\Foundation\Database\Migrations;
use Illuminate\Container\Container;
use Illuminate\Database\Migrations\Migration as IlluminateMigration;
/**
 * Class Migration
 * @package Notadd\Foundation\Database\Migrations
 */
abstract class Migration extends IlluminateMigration {
    /**
     * @var \Illuminate\Database\Schema\Builder
     */
    protected $schema;
    /**
     * Migration constructor.
     */
    public function __construct() {
        $this->schema = Container::getInstance()->make('db')->connection()->getSchemaBuilder();
    }
}