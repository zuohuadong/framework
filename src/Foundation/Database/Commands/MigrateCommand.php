<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-08 19:23
 */
namespace Notadd\Foundation\Database\Commands;
use Notadd\Foundation\Console\Abstracts\AbstractCommand;
use Notadd\Foundation\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;
/**
 * Class MigrateCommand
 * @package Notadd\Foundation\Database\Commands
 */
class MigrateCommand extends AbstractCommand {
    /**
     * @var \Notadd\Foundation\Database\Migrations\Migrator
     */
    protected $migrator;
    /**
     * MigrateCommand constructor.
     * @param \Notadd\Foundation\Database\Migrations\Migrator $migrator
     */
    public function __construct(Migrator $migrator) {
        parent::__construct();
        $this->migrator = $migrator;
    }
    /**
     * @return void
     */
    protected function configure() {
        $this->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.');
        $this->addOption('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.');
        $this->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The path of migrations files to be executed.');
        $this->addOption('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.');
        $this->addOption('seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.');
        $this->addOption('step', null, InputOption::VALUE_NONE, 'Force the migrations to be run so they can be rolled back individually.');
        $this->setDescription('Run the database migrations');
        $this->setName('migrate');
    }
    /**
     * @return void
     */
    protected function fire() {
        $this->prepareDatabase();
        $this->migrator->run($this->getMigrationPath(), [
            'pretend' => $this->input->getOption('pretend'),
            'step' => $this->input->getOption('step'),
        ]);
        foreach($this->migrator->getNotes() as $note) {
            $this->output->writeln($note);
        }
        if($this->input->getOption('seed')) {
            $this->call('db:seed', ['--force' => true]);
        }
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
     * @return void
     */
    protected function prepareDatabase() {
        $this->migrator->setConnection($this->input->getOption('database'));
        if(!$this->migrator->repositoryExists()) {
            $options = ['--database' => $this->input->getOption('database')];
            $this->call('migrate:install', $options);
        }
    }
}