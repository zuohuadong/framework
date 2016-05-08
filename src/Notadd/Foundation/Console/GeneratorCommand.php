<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 17:50
 */
namespace Notadd\Foundation\Console;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
/**
 * Class GeneratorCommand
 * @package Notadd\Foundation\Console
 */
abstract class GeneratorCommand extends Command {
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * @var string
     */
    protected $type;
    /**
     * GeneratorCommand constructor.
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Filesystem $files) {
        parent::__construct();
        $this->files = $files;
    }
    /**
     * @return string
     */
    abstract protected function getStub();
    /**
     * @return bool|null
     */
    public function fire() {
        $name = $this->parseName($this->getNameInput());
        $path = $this->getPath($name);
        if($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type . ' already exists!');
            return false;
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClass($name));
        $this->info($this->type . ' created successfully.');
    }
    /**
     * @param string $rawName
     * @return bool
     */
    protected function alreadyExists($rawName) {
        $name = $this->parseName($rawName);
        return $this->files->exists($path = $this->getPath($name));
    }
    /**
     * @param string $name
     * @return string
     */
    protected function getPath($name) {
        $name = str_replace($this->laravel->getNamespace(), '', $name);
        return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . '.php';
    }
    /**
     * @param string $name
     * @return string
     */
    protected function parseName($name) {
        $rootNamespace = $this->laravel->getNamespace();
        if(Str::startsWith($name, $rootNamespace)) {
            return $name;
        }
        if(Str::contains($name, '/')) {
            $name = str_replace('/', '\\', $name);
        }
        return $this->parseName($this->getDefaultNamespace(trim($rootNamespace, '\\')) . '\\' . $name);
    }
    /**
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace) {
        return $rootNamespace;
    }
    /**
     * @param string $path
     * @return string
     */
    protected function makeDirectory($path) {
        if(!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }
    /**
     * @param string $name
     * @return string
     */
    protected function buildClass($name) {
        $stub = $this->files->get($this->getStub());
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }
    /**
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name) {
        $stub = str_replace('DummyNamespace', $this->getNamespace($name), $stub);
        $stub = str_replace('DummyRootNamespace', $this->laravel->getNamespace(), $stub);
        return $this;
    }
    /**
     * @param string $name
     * @return string
     */
    protected function getNamespace($name) {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }
    /**
     * @param string $stub
     * @param string $name
     * @return string
     */
    protected function replaceClass($stub, $name) {
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);
        return str_replace('DummyClass', $class, $stub);
    }
    /**
     * @return string
     */
    protected function getNameInput() {
        return $this->argument('name');
    }
    /**
     * @return array
     */
    protected function getArguments() {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of the class'
            ],
        ];
    }
}