<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 16:27
 */
namespace Notadd\Foundation\Console;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\MountManager;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Adapter\Local as LocalAdapter;
/**
 * Class VendorPublishCommand
 * @package Notadd\Foundation\Console
 */
class VendorPublishCommand extends Command {
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * @var string
     */
    protected $signature = 'vendor:publish {--force : Overwrite any existing files.}
            {--provider= : The service provider that has assets you want to publish.}
            {--tag=* : One or many tags that have assets you want to publish.}';
    /**
     * @var string
     */
    protected $description = 'Publish any publishable assets from vendor packages';
    /**
     * VendorPublishCommand constructor.
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
        $tags = $this->option('tag');
        $tags = $tags ?: [null];
        foreach($tags as $tag) {
            $this->publishTag($tag);
        }
    }
    /**
     * @param string $tag
     * @return mixed
     */
    private function publishTag($tag) {
        $paths = ServiceProvider::pathsToPublish($this->option('provider'), $tag);
        if(empty($paths)) {
            return $this->comment("Nothing to publish for tag [{$tag}].");
        }
        foreach($paths as $from => $to) {
            if($this->files->isFile($from)) {
                $this->publishFile($from, $to);
            } elseif($this->files->isDirectory($from)) {
                $this->publishDirectory($from, $to);
            } else {
                $this->error("Can't locate path: <{$from}>");
            }
        }
        $this->info("Publishing complete for tag [{$tag}]!");
    }
    /**
     * @param string $from
     * @param string $to
     * @return void
     */
    protected function publishFile($from, $to) {
        if($this->files->exists($to) && !$this->option('force')) {
            return;
        }
        $this->createParentDirectory(dirname($to));
        $this->files->copy($from, $to);
        $this->status($from, $to, 'File');
    }
    /**
     * @param string $from
     * @param string $to
     * @return void
     */
    protected function publishDirectory($from, $to) {
        $manager = new MountManager([
            'from' => new Flysystem(new LocalAdapter($from)),
            'to' => new Flysystem(new LocalAdapter($to)),
        ]);
        foreach($manager->listContents('from://', true) as $file) {
            if($file['type'] === 'file' && (!$manager->has('to://' . $file['path']) || $this->option('force'))) {
                $manager->put('to://' . $file['path'], $manager->read('from://' . $file['path']));
            }
        }
        $this->status($from, $to, 'Directory');
    }
    /**
     * @param string $directory
     * @return void
     */
    protected function createParentDirectory($directory) {
        if(!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }
    }
    /**
     * @param string $from
     * @param string $to
     * @param string $type
     * @return void
     */
    protected function status($from, $to, $type) {
        $from = str_replace(base_path(), '', realpath($from));
        $to = str_replace(base_path(), '', realpath($to));
        $this->line('<info>Copied ' . $type . '</info> <comment>[' . $from . ']</comment> <info>To</info> <comment>[' . $to . ']</comment>');
    }
}