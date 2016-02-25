<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-02 23:38
 */
namespace Notadd\Theme;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Notadd\Theme\Contracts\Theme as ThemeContract;
/**
 * Class Theme
 * @package Notadd\Theme
 */
class Theme implements ThemeContract {
    /**
     * @var \Notadd\Foundation\Application
     */
    private $application;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $alias;
    /**
     * @var string
     */
    private $cssPath;
    /**
     * @var string
     */
    private $fontPath;
    /**
     * @var string
     */
    private $jsPath;
    /**
     * @var string
     */
    private $lessPath;
    /**
     * @var string
     */
    private $imagePath;
    /**
     * @var \Illuminate\Support\Collection
     */
    private $publishData;
    /**
     * @var string
     */
    private $sassPath;
    /**
     * @var \Notadd\Setting\Factory
     */
    private $setting;
    /**
     * @var string
     */
    private $viewPath;
    /**
     * Theme constructor.
     * @param $title
     * @param $alias
     */
    public function __construct($title, $alias) {
        $this->alias = $alias;
        $this->application = Container::getInstance();
        $this->publishData = new Collection();
        $this->setting = $this->application->make('setting');
        $this->title = $title;
    }
    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }
    /**
     * @return string
     */
    public function getAlias() {
        return $this->alias;
    }
    /**
     * @return string
     */
    public function getCssPath() {
        return $this->cssPath;
    }
    /**
     * @param string $path
     * @return mixed|void
     */
    public function useCssPath($path) {
        $this->cssPath = $path;
    }
    /**
     * @return string
     */
    public function getFontPath() {
        return $this->fontPath;
    }
    /**
     * @param string $path
     * @return mixed|void
     */
    public function useFontPath($path) {
        $this->fontPath = $path;
    }
    /**
     * @return string
     */
    public function getImagePath() {
        return $this->imagePath;
    }
    /**
     * @param string $path
     * @return mixed|void
     */
    public function useImagePath($path) {
        $this->imagePath = $path;
    }
    /**
     * @return string
     */
    public function getJsPath() {
        return $this->jsPath;
    }
    /**
     * @param $path
     * @return mixed|void
     */
    public function useJsPath($path) {
        $this->jsPath = $path;
    }
    /**
     * @return string
     */
    public function getLessPath() {
        return $this->lessPath;
    }
    /**
     * @param $path
     * @return string
     */
    public function useLessPath($path) {
        $this->lessPath = $path;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getPublishData() {
        return $this->publishData;
    }
    /**
     * @param $key
     * @param $value
     */
    public function usePublishPath($key, $value) {
        $this->publishData->put($key, $value);
    }
    /**
     * @return string
     */
    public function getSassPath() {
        return $this->sassPath;
    }
    /**
     * @param $path
     */
    public function useSassPath($path) {
        $this->sassPath = $path;
    }
    /**
     * @return string
     */
    public function getViewPath() {
        return $this->viewPath;
    }
    /**
     * @param string $path
     * @return mixed|void
     */
    public function useViewPath($path) {
        $this->viewPath = $path;
    }
    /**
     * @return bool
     */
    public function isDefault() {
        if($this->setting->get('site.theme') === $this->alias) {
            return true;
        }
        return false;
    }
    /**
     * @param string $path
     * @return string
     */
    protected function getDefaultStaticPath($path = '') {
        $defaultPath = $this->application->publicPath() . DIRECTORY_SEPARATOR . 'statics' . DIRECTORY_SEPARATOR . $this->alias;
        return $path ? $defaultPath . DIRECTORY_SEPARATOR . $path : $defaultPath;
    }
}