<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 18:13
 */
namespace Notadd\Foundation\Queue\Console;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;
/**
 * Class FailedTableCommand
 * @package Notadd\Foundation\Queue\Console
 */
class FailedTableCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'queue:failed-table';
    /**
     * @var string
     */
    protected $description = 'Create a migration for the failed queue jobs database table';
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * @var \Illuminate\Support\Composer
     */
    protected $composer;
    /**
     * FailedTableCommand constructor.
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \Illuminate\Support\Composer $composer
     */
    public function __construct(Filesystem $files, Composer $composer) {
        parent::__construct();
        $this->files = $files;
        $this->composer = $composer;
    }
    /**
     * Execute the console command.
     * @return void
     */
    public function fire() {
        $table = $this->laravel['config']['queue.failed.table'];
        $tableClassName = Str::studly($table);
        $fullPath = $this->createBaseMigration($table);
        $stub = str_replace([
            '{{table}}',
            '{{tableClassName}}'
        ], [
            $table,
            $tableClassName
        ], $this->files->get(__DIR__ . '/stubs/failed_jobs.stub'));
        $this->files->put($fullPath, $stub);
        $this->info('Migration created successfully!');
        $this->composer->dumpAutoloads();
    }
    /**
     * @param string $table
     * @return string
     */
    protected function createBaseMigration($table = 'failed_jobs') {
        $name = 'create_' . $table . '_table';
        $path = $this->laravel->frameworkPath() . '/migrations';
        return $this->laravel['migration.creator']->create($name, $path);
    }
}