<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 16:29
 */
namespace Notadd\Foundation\Console;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
/**
 * Class ViewClearCommand
 * @package Notadd\Foundation\Console
 */
class ViewClearCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'view:clear';
    /**
     * @var string
     */
    protected $description = 'Clear all compiled view files';
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * ViewClearCommand constructor.
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
        $views = $this->files->glob($this->laravel['config']['view.compiled'] . '/*');
        foreach($views as $view) {
            $this->files->delete($view);
        }
        $this->info('Compiled views cleared!');
    }
}