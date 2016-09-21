<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 10:37
 */
namespace Notadd\Foundation\Database\Migrations;
use Illuminate\Container\Container;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Database\Migrations\Migrator as IlluminateMigrator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
/**
 * Class Migrator
 * @package Notadd\Foundation\Database\Migrations
 */
class Migrator extends IlluminateMigrator {
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;
    /**
     * Migrator constructor.
     * @param \Illuminate\Container\Container $container
     * @param \Illuminate\Database\Migrations\MigrationRepositoryInterface $repository
     * @param \Illuminate\Database\ConnectionResolverInterface $resolver
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Container $container, MigrationRepositoryInterface $repository, Resolver $resolver, Filesystem $files) {
        $this->container = $container;
        parent::__construct($repository, $resolver, $files);
    }
    /**
     * @param string $file
     * @return mixed
     */
    public function resolve($file) {
        $class = Str::studly(implode('_', array_slice(explode('_', $file), 4)));
        return $this->container->make($class);
    }
}