<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 16:00
 */
namespace Notadd\Foundation\Console;
use Illuminate\Console\Command;
/**
 * Class ClearCompiledCommand
 * @package Notadd\Foundation\Console
 */
class ClearCompiledCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'clear-compiled';
    /**
     * @var string
     */
    protected $description = 'Remove the compiled class file';
    /**
     * @return void
     */
    public function fire() {
        $compiledPath = $this->laravel->getCachedCompilePath();
        $servicesPath = $this->laravel->getCachedServicesPath();
        if(file_exists($compiledPath)) {
            @unlink($compiledPath);
        }
        if(file_exists($servicesPath)) {
            @unlink($servicesPath);
        }
    }
}