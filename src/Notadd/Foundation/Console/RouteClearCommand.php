<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 16:17
 */
namespace Notadd\Foundation\Console;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
/**
 * Class RouteClearCommand
 * @package Notadd\Foundation\Console
 */
class RouteClearCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'route:clear';
    /**
     * @var string
     */
    protected $description = 'Remove the route cache file';
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * RouteClearCommand constructor.
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
        $this->files->delete($this->laravel->getCachedRoutesPath());
        $this->info('Route cache cleared!');
    }
}