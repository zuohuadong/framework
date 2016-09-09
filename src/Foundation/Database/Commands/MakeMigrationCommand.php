<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-08 19:01
 */
namespace Notadd\Foundation\Database\Commands;
use Illuminate\Support\Composer;
use Notadd\Foundation\Console\Abstracts\AbstractCommand;
use Notadd\Foundation\Database\Migrations\MigrationCreator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
/**
 * Class MakeMigration
 * @package Foundation\Database\Commands
 */
class MakeMigrationCommand extends AbstractCommand {
    /**
     * @var \Illuminate\Database\Migrations\MigrationCreator
     */
    protected $creator;
    /**
     * @var \Illuminate\Support\Composer
     */
    protected $composer;
    /**
     * MakeMigrationCommand constructor.
     * @param \Notadd\Foundation\Database\Migrations\MigrationCreator $creator
     * @param \Illuminate\Support\Composer $composer
     */
    public function __construct(MigrationCreator $creator, Composer $composer) {
        parent::__construct();
        $this->composer = $composer;
        $this->creator = $creator;
    }
    /**
     * @return void
     */
    public function configure() {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the migration.');
        $this->addOption('create', null, InputOption::VALUE_OPTIONAL, 'The table to be created.');
        $this->addOption('table', null, InputOption::VALUE_OPTIONAL, 'The table to migrate.');
        $this->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The location where the migration file should be created.');
        $this->setDescription('Create a new migration file');
        $this->setName('make:migration');
    }
    /**
     * @return void
     */
    public function fire() {
        $create = $this->input->getOption('create') ?: false;
        $name = $this->input->getArgument('name');
        $table = $this->input->getOption('table');
        if(!$table && is_string($create)) {
            $table = $create;
            $create = true;
        }
        $this->writeMigration($name, $table, $create);
        $this->composer->dumpAutoloads();
    }
    /**
     * @return string
     */
    protected function getMigrationPath() {
        if(!is_null($targetPath = $this->input->getOption('path'))) {
            return $this->container->basePath() . '/' . $targetPath;
        }
        return realpath(__DIR__ . '/../../../../migrations');
    }
    /**
     * @param $name
     * @param $table
     * @param $create
     */
    protected function writeMigration($name, $table, $create) {
        $path = $this->getMigrationPath();
        $file = pathinfo($this->creator->create($name, $path, $table, $create), PATHINFO_FILENAME);
        $this->info("<info>Created Migration:</info> {$file}");
    }
}