<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-01-29 15:07
 */
namespace Notadd\Foundation\Console;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
/**
 * Class StaticClearCommand
 * @package Notadd\Foundation\Console
 */
class StaticClearCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'static:clear';
    /**
     * @var string
     */
    protected $description = 'Clear all static files';
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * StaticClearCommand constructor.
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
        $statics = $this->files->glob($this->laravel->publicPath() . DIRECTORY_SEPARATOR . 'caches' . '/*');
        foreach($statics as $static) {
            $this->files->deleteDirectory($static);
        }
        $this->info('Static files cleared!');
    }
}