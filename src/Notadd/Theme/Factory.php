<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-29 16:32
 */
namespace Notadd\Theme;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
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
     * Factory constructor.
     * @param \Illuminate\Contracts\Foundation\Application $application
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \Notadd\Theme\FileFinder $finder
     */
    public function __construct(Application $application, Filesystem $files, FileFinder $finder) {
        $this->application = $application;
        $this->files = $files;
        $this->finder = $finder;
        $this->buildThemeList();
    }
    /**
     * @return void
     */
    protected function buildThemeList() {
        $list = Collection::make();
        $default = new Theme('默认模板', 'default');
        $default->useViewPath(realpath($this->application->frameworkPath() . '/views/default'));
        $default->usePublishPath(framework_path('statics/admin'), public_path('statics/admin'));
        $default->usePublishPath(framework_path('statics/ueditor'), public_path('statics/ueditor'));
        $list->put('default', $default);
        $admin = new Theme('后台模板', 'admin');
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
}