<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 19:51
 */
namespace Notadd\Foundation\Session\Console;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Notadd\Foundation\Console\Command;
/**
 * Class SessionTableCommand
 * @package Notadd\Foundation\Session\Console
 */
class SessionTableCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'session:table';
    /**
     * @var string
     */
    protected $description = 'Create a migration for the session database table';
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * @var \Illuminate\Support\Composer
     */
    protected $composer;
    /**
     * SessionTableCommand constructor.
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \Illuminate\Support\Composer $composer
     */
    public function __construct(Filesystem $files, Composer $composer) {
        parent::__construct();
        $this->files = $files;
        $this->composer = $composer;
    }
    /**
     * @return void
     */
    public function fire() {
        $fullPath = $this->createBaseMigration();
        $this->files->put($fullPath, $this->files->get(__DIR__ . '/stubs/database.stub'));
        $this->info('Migration created successfully!');
        $this->composer->dumpAutoloads();
    }
    /**
     * @return string
     */
    protected function createBaseMigration() {
        $name = 'create_sessions_table';
        $path = $this->notadd->frameworkPath() . '/migrations';
        return $this->notadd['migration.creator']->create($name, $path);
    }
}