<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-17 20:41
 */
namespace Notadd\Extension;
use Illuminate\Contracts\Foundation\Application;
/**
 * Class ExtensionManager
 * @package Notadd\Foundation\Extension
 */
class ExtensionManager {
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $application;
    /**
     * @var
     */
    protected $config;
    /**
     * @var
     */
    protected $migrator;
    /**
     * @param \Illuminate\Contracts\Foundation\Application $application
     */
    public function __construct(Application $application) {
        $this->application = $application;
    }
    /**
     * @return void
     */
    public function getInfo() {
    }
    /**
     * @param $extension
     */
    public function enable($extension) {
    }
    /**
     * @param $extension
     */
    public function disable($extension) {
    }
    /**
     * @param $extension
     */
    public function uninstall($extension) {
    }
    /**
     * @param $extension
     * @param bool $up
     */
    public function migrate($extension, $up = true) {
    }
    /**
     * @return mixed
     */
    public function getMigrator() {
        return $this->migrator;
    }
    /**
     * @return void
     */
    protected function getEnabled() {
    }
    /**
     * @param array $enabled
     */
    protected function setEnabled(array $enabled) {
    }
    /**
     * @param $extension
     */
    public function isEnabled($extension) {
    }
    /**
     * @param $extension
     */
    protected function load($extension) {
    }
    /**
     * @return string
     */
    public function getExtensionsDir() {
        return realpath(base_path('extensions'));
    }
}