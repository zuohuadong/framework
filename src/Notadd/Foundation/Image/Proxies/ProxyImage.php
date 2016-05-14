<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:59
 */
namespace Notadd\Foundation\Image\Proxies;
use Notadd\Foundation\Image\Contracts\Image;
use Notadd\Foundation\Image\Contracts\Resolver;
/**
 * Class ProxyImage
 * @package Notadd\Foundation\Image\Proxies
 */
class ProxyImage implements Image {
    /**
     * @var callable
     */
    protected $initializer;
    /**
     * @var boolean
     */
    protected $invoked;
    /**
     * ProxyImage constructor.
     * @param callable $initializer
     */
    public function __construct(callable $initializer) {
        $this->invoked = false;
        $this->initializer = $initializer;
    }
    /**
     * @return void
     */
    public function __destruct() {
        $this->close();
    }
    /**
     * @param string $source
     * @return mixed
     */
    public function load($source) {
        return $this->envokeObjectMethod('load', [$source]);
    }
    /**
     * @param \Notadd\Foundation\Image\Contracts\Resolver $resolver
     * @return mixed
     */
    public function process(Resolver $resolver) {
        return $this->envokeObjectMethod('process', [$resolver]);
    }
    /**
     * @param int $quality
     * @return mixed
     */
    public function setQuality($quality) {
        return $this->envokeObjectMethod('setQuality', [$quality]);
    }
    /**
     * @param string $format
     * @return mixed
     */
    public function setFileFormat($format) {
        return $this->envokeObjectMethod('setFileFormat', [$format]);
    }
    /**
     * @return mixed
     */
    public function getFileFormat() {
        return $this->envokeObjectMethod('getFileFormat');
    }
    /**
     * @return mixed
     */
    public function getSourceMimeTime() {
        return $this->envokeObjectMethod('getSourceMimeTime');
    }
    /**
     * @return mixed
     */
    public function getMimeType() {
        return $this->envokeObjectMethod('getMimeType');
    }
    /**
     * @return mixed
     */
    public function getSource() {
        return $this->envokeObjectMethod('getSource');
    }
    /**
     * @return mixed
     */
    public function isProcessed() {
        return $this->envokeObjectMethod('isProcessed');
    }
    /**
     * @return mixed
     */
    public function getLastModTime() {
        return $this->envokeObjectMethod('getLastModTime');
    }
    /**
     * @return mixed
     */
    public function getSourceFormat() {
        return $this->envokeObjectMethod('getSourceFormat');
    }
    /**
     * @return mixed
     */
    public function close() {
        if($this->invoked) {
            return $this->envokeObjectMethod('close');
        }
    }
    /**
     * @return mixed
     */
    public function getContents() {
        return $this->envokeObjectMethod('getContents');
    }
    /**
     * @param $method
     * @param array $arguments
     * @return mixed
     */
    private function envokeObjectMethod($method, array $arguments = []) {
        if(!$this->invoked) {
            $this->invokeInitializer();
        }
        return call_user_func_array([
            $this->object,
            $method
        ], $arguments);
    }
    /**
     * @return void
     */
    private function invokeInitializer() {
        $this->invoked = true;
        $this->object = call_user_func($this->initializer);
    }
}