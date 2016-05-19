<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 15:10
 */
namespace Notadd\Image;
use Notadd\Image\Exceptions\NotSupportedException;
use ReflectionClass;
/**
 * Class AbstractDriver
 * @package Notadd\Image
 */
abstract class AbstractDriver {
    /**
     * @var \Notadd\Image\AbstractDecoder
     */
    public $decoder;
    /**
     * @var \Notadd\Image\AbstractEncoder
     */
    public $encoder;
    /**
     * @param  integer $width
     * @param  integer $height
     * @param  string $background
     * @return \Notadd\Image\Image
     */
    abstract public function newImage($width, $height, $background);
    /**
     * @param  string $value
     * @return AbstractColor
     */
    abstract public function parseColor($value);
    /**
     * @return boolean
     */
    abstract protected function coreAvailable();
    /**
     * @return mixed
     */
    public function cloneCore($core) {
        return clone $core;
    }
    /**
     * @param  mixed $data
     * @return \Notadd\Image\Image
     */
    public function init($data) {
        return $this->decoder->init($data);
    }
    /**
     * @param  Image $image
     * @param  string $format
     * @param  integer $quality
     * @return \Notadd\Image\Image
     */
    public function encode($image, $format, $quality) {
        return $this->encoder->process($image, $format, $quality);
    }
    /**
     * @param  Image $image
     * @param  string $name
     * @param  array $arguments
     * @return \Notadd\Image\Commands\AbstractCommand
     */
    public function executeCommand($image, $name, $arguments) {
        $commandName = $this->getCommandClassName($name);
        $command = new $commandName($arguments);
        $command->execute($image);
        return $command;
    }
    /**
     * @param  string $name
     * @return string
     * @throws \Notadd\Image\Exceptions\NotSupportedException
     */
    private function getCommandClassName($name) {
        $drivername = $this->getDriverName();
        $classnameLocal = sprintf('\Notadd\Image\%s\Commands\%sCommand', $drivername, ucfirst($name));
        $classnameGlobal = sprintf('\Notadd\Image\Commands\%sCommand', ucfirst($name));
        if(class_exists($classnameLocal)) {
            return $classnameLocal;
        } elseif(class_exists($classnameGlobal)) {
            return $classnameGlobal;
        }
        throw new NotSupportedException("Command ({$name}) is not available for driver ({$drivername}).");
    }
    /**
     * @return string
     */
    public function getDriverName() {
        $reflect = new ReflectionClass($this);
        $namespace = $reflect->getNamespaceName();
        return substr(strrchr($namespace, "\\"), 1);
    }
}