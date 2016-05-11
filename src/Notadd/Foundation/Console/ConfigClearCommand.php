<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 16:05
 */
namespace Notadd\Foundation\Console;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
/**
 * Class ConfigClearCommand
 * @package Notadd\Foundation\Console
 */
class ConfigClearCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'config:clear';
    /**
     * @var string
     */
    protected $description = 'Remove the configuration cache file';
    protected $files;
    /**
     * ConfigClearCommand constructor.
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Filesystem $files) {
        parent::__construct();
        $this->files = $files;
    }
    /**
     * @return void
     */
    public function fire() {
        $this->files->delete($this->laravel->getCachedConfigPath());
        $this->info('Configuration cache cleared!');
    }
}