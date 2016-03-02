<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-29 16:32
 */
namespace Notadd\Theme;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Notadd\Theme\Contracts\Factory as FactoryContract;
use Notadd\Theme\Events\GetThemeList;
/**
 * Class Factory
 * @package Notadd\Theme
 */
class Factory implements FactoryContract {
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private $application;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $files;
    /**
     * @var \Notadd\Theme\FileFinder
     */
    private $finder;
    /**
     * @var \Illuminate\Support\Collection
     */
    private $list;
    /**
     * @var \Notadd\Theme\Material
     */
    private $material;
    /**
     * Factory constructor.
     * @param \Illuminate\Contracts\Foundation\Application $application
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \Notadd\Theme\FileFinder $finder
     * @param \Notadd\Theme\Material $material
     */
    public function __construct(Application $application, Filesystem $files, FileFinder $finder, Material $material) {
        $this->application = $application;
        $this->files = $files;
        $this->finder = $finder;
        $this->material = $material;
        $this->material->setTheme($this);
        $this->buildThemeList();
    }
    /**
     * @return void
     */
    protected function buildThemeList() {
        $list = Collection::make();
        $default = new Theme('默认模板', 'default');
        $default->useCssPath(realpath($this->application->frameworkPath() . '/less'));
        $default->useLessPath(realpath($this->application->frameworkPath() . '/less'));
        $default->useViewPath(realpath($this->application->frameworkPath() . '/views/default'));
        $default->usePublishPath(framework_path('statics/admin'), public_path('statics/admin'));
        $default->usePublishPath(framework_path('statics/ueditor'), public_path('statics/ueditor'));
        $list->put('default', $default);
        $admin = new Theme('后台模板', 'admin');
        $admin->useCssPath(realpath($this->application->frameworkPath() . '/less'));
        $admin->useLessPath(realpath($this->application->frameworkPath() . '/less'));
        $admin->useViewPath(realpath($this->application->frameworkPath() . '/views/admin'));
        $list->put('admin', $admin);
        $this->application->make('events')->fire(new GetThemeList($this->application, $list));
        $this->list = $list;
    }
    /**
     * @param string $alias
     * @return \Notadd\Theme\Theme
     */
    public function getTheme($alias) {
        return $this->list->get($alias);
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getThemeList() {
        return $this->list;
    }
    /**
     * @param string $path
     * @return mixed|void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function registerCss($path) {
        if($this->finder->exits($path)) {
            switch(str_replace(Str::substr($path, strpos($path, '.')), '', Str::substr($path, strpos($path, '::') + 2))) {
                case 'css':
                    $this->material->registerCssMaterial($path);
                    break;
                case 'less':
                    $this->material->registerLessMaterial($path);
                    break;
                case 'sass':
                    $this->material->registerSassMaterial($path);
                    break;
            }
        } else {
            throw new FileNotFoundException('Css file [' . $path . '] does not exits!');
        }
    }
    /**
     * @param string $path
     * @return mixed|void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function registerJs($path) {
        if($this->finder->exits($path)) {
            $this->material->registerJsMaterial($path);
        } else {
            throw new FileNotFoundException('Js file [' . $path . '] does not exits!');
        }
    }
    /**
     * @param string $type
     * @return string
     */
    public function outputInBlade($type = 'css') {
        $output = '';
        switch($type) {
            case 'css':
                $output = $this->material->outputCssInBlade();
                break;
            case 'js':
                $output = $this->material->outputJsInBlade();
                break;
        }
        return $output;
    }
}