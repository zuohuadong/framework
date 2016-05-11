<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 16:02
 */
namespace Notadd\Foundation\Console;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
/**
 * Class ConfigCacheCommand
 * @package Notadd\Foundation\Console
 */
class ConfigCacheCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'config:cache';
    /**
     * @var string
     */
    protected $description = 'Create a cache file for faster configuration loading';
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * ConfigCacheCommand constructor.
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
        $this->call('config:clear');
        $config = $this->getFreshConfiguration();
        $this->files->put($this->laravel->getCachedConfigPath(), '<?php return ' . var_export($config, true) . ';' . PHP_EOL);
        $this->info('Configuration cached successfully!');
    }
    /**
     * @return mixed
     */
    protected function getFreshConfiguration() {
        $app = require $this->laravel->basePath() . '/bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app['config']->all();
    }
}