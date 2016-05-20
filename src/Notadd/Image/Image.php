<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 15:19
 */
namespace Notadd\Image;
use Notadd\Image\Exceptions\NotWritableException;
use Notadd\Image\Exceptions\RuntimeException;
use Notadd\Image\Filters\FilterInterface;
/**
 * Class Image
 * @package Notadd\Image
 */
class Image extends File {
    /**
     * @var AbstractDriver
     */
    protected $driver;
    /**
     * @var mixed
     */
    protected $core;
    /**
     * @var array
     */
    protected $backups = array();
    /**
     * @var string
     */
    public $encoded = '';
    /**
     * @param AbstractDriver $driver
     * @param mixed $core
     */
    public function __construct(AbstractDriver $driver = null, $core = null) {
        $this->driver = $driver;
        $this->core = $core;
    }
    /**
     * @param $name
     * @param $arguments
     * @return mixed|\Notadd\Image\Image
     */
    public function __call($name, $arguments) {
        $command = $this->driver->executeCommand($this, $name, $arguments);
        return $command->hasOutput() ? $command->getOutput() : $this;
    }
    /**
     * @param  string $format
     * @param  integer $quality
     * @return \Notadd\Image\Image
     */
    public function encode($format = null, $quality = 90) {
        return $this->driver->encode($this, $format, $quality);
    }
    /**
     * @param  string $path
     * @param  integer $quality
     * @return \Notadd\Image\Image
     * @throws \Notadd\Image\Exceptions\NotWritableException
     */
    public function save($path = null, $quality = null) {
        $path = is_null($path) ? $this->basePath() : $path;
        if(is_null($path)) {
            throw new NotWritableException("Can't write to undefined path.");
        }
        $data = $this->encode(pathinfo($path, PATHINFO_EXTENSION), $quality);
        $saved = @file_put_contents($path, $data);
        if($saved === false) {
            throw new NotWritableException("Can't write image data to path ({$path})");
        }
        $this->setFileInfoFromPath($path);
        return $this;
    }
    /**
     * @param \Notadd\Image\Filters\FilterInterface $filter
     * @return \Notadd\Image\Image
     */
    public function filter(FilterInterface $filter) {
        return $filter->applyFilter($this);
    }
    /**
     * @return \Notadd\Image\AbstractDriver
     */
    public function getDriver() {
        return $this->driver;
    }
    /**
     * @param \Notadd\Image\AbstractDriver $driver
     * @return $this
     */
    public function setDriver(AbstractDriver $driver) {
        $this->driver = $driver;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getCore() {
        return $this->core;
    }
    /**
     * @param $core
     * @return $this
     */
    public function setCore($core) {
        $this->core = $core;
        return $this;
    }
    /**
     * @param null $name
     * @return mixed
     * @throws RuntimeException
     */
    public function getBackup($name = null) {
        $name = is_null($name) ? 'default' : $name;
        if(!$this->backupExists($name)) {
            throw new RuntimeException("Backup with name ({$name}) not available. Call backup() before reset().");
        }
        return $this->backups[$name];
    }
    /**
     * @return array
     */
    public function getBackups() {
        return $this->backups;
    }
    /**
     * @param mixed $resource
     * @param string $name
     * @return self
     */
    public function setBackup($resource, $name = null) {
        $name = is_null($name) ? 'default' : $name;
        $this->backups[$name] = $resource;
        return $this;
    }
    /**
     * @param  string $name
     * @return bool
     */
    private function backupExists($name) {
        return array_key_exists($name, $this->backups);
    }
    /**
     * @return boolean
     */
    public function isEncoded() {
        return !empty($this->encoded);
    }
    /**
     * @return string
     */
    public function getEncoded() {
        return $this->encoded;
    }
    /**
     * @param string $value
     * @return $this
     */
    public function setEncoded($value) {
        $this->encoded = $value;
        return $this;
    }
    /**
     * @return integer
     */
    public function getWidth() {
        return $this->getSize()->width;
    }
    /**
     * @return integer
     */
    public function width() {
        return $this->getWidth();
    }
    /**
     * @return integer
     */
    public function getHeight() {
        return $this->getSize()->height;
    }
    /**
     * @return integer
     */
    public function height() {
        return $this->getHeight();
    }
    /**
     * @return string
     */
    public function mime() {
        return $this->mime;
    }
    /**
     * @return string
     */
    public function __toString() {
        return $this->encoded;
    }
    /**
     * @return void
     */
    public function __clone() {
        $this->core = $this->driver->cloneCore($this->core);
    }
}