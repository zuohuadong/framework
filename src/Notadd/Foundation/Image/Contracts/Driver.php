<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:45
 */
namespace Notadd\Foundation\Image\Contracts;
/**
 * Interface Driver
 * @package Notadd\Foundation\Image\Contracts
 */
interface Driver {
    /**
     * @param  string $source
     * @return void
     */
    public function load($source);
    /**
     * @param string $name
     * @param array $options
     * @return boolean|void
     */
    public function filter($name, array $options = []);
    /**
     * @return void
     */
    public function process();
    /**
     * @return void
     */
    public function clean();
    /**
     * @return string|boolean false
     */
    public function getError();
    /**
     * @param  string $alias
     * @param  string $class
     * @return void
     */
    public function registerFilter($alias, $class);
    /**
     * @param mixed $type
     * @return void
     */
    public function setOutPutType($type);
    /**
     * @param bool $assSuffix
     * @return string
     */
    public function getSourceType($assSuffix = false);
    /**
     * @return string
     */
    public function getImageBlob();
    /**
     * @return array
     */
    public function getTargetSize();
    /**
     * @return mixed
     */
    public function getResource();
    /**
     * @param mixed $resource
     * @return void
     */
    public function swapResource($resource);
    /**
     * @return string
     */
    public function getDriverType();
    /**
     * @return bool
     */
    public function isProcessed();
}