<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-10 13:27
 */
namespace Notadd\Foundation\Database\Commands;
use Notadd\Foundation\Console\Abstracts\AbstractCommand;
use Notadd\Foundation\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;
/**
 * Class RollbackCommand
 * @package Notadd\Foundation\Database\Commands
 */
class RollbackCommand extends AbstractCommand {
    /**
     * @var \Notadd\Foundation\Database\Migrations\Migrator
     */
    protected $migrator;
    /**
     * RollbackCommand constructor.
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
        $this->addOption('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.');
        $this->addOption('step', null, InputOption::VALUE_OPTIONAL, 'The number of migrations to be reverted.');
        $this->setDescription('Rollback the last database migration');
        $this->setName('migrate:rollback');
    }
    /**
     * @return void
     */
    protected function fire() {
        $this->migrator->setConnection($this->input->getOption('database'));
        $this->migrator->rollback($this->getMigrationPath(), [
            'pretend' => $this->input->getOption('pretend'),
            'step' => (int)$this->input->getOption('step')
        ]);
        foreach($this->migrator->getNotes() as $note) {
            $this->output->writeln($note);
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
}