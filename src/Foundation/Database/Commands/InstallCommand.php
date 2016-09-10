<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-08 19:59
 */
namespace Notadd\Foundation\Database\Commands;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Notadd\Foundation\Console\Abstracts\AbstractCommand;
use Symfony\Component\Console\Input\InputOption;
/**
 * Class InstallCommand
 * @package Notadd\Foundation\Database\Commands
 */
class InstallCommand extends AbstractCommand {
    /**
     * @var \Illuminate\Database\Migrations\MigrationRepositoryInterface
     */
    protected $repository;
    /**
     * InstallCommand constructor.
     * @param \Illuminate\Database\Migrations\MigrationRepositoryInterface $repository
     */
    public function __construct(MigrationRepositoryInterface $repository) {
        parent::__construct();
        $this->repository = $repository;
    }
    /**
     * @return void
     */
    protected function configure() {
        $this->setDescription('Create the migration repository');
        $this->setName('migrate:install');
        $this->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.');
    }
    /**
     * @return void
     */
    protected function fire() {
        $this->repository->setSource($this->input->getOption('database'));
        $this->repository->createRepository();
        $this->info('Migration table created successfully.');
    }
}