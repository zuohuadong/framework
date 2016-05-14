<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:29
 */
namespace Notadd\Foundation\Image\Contracts;
/**
 * Interface Image
 * @package Notadd\Foundation\Image\Contracts
 */
interface Image {
    /**
     * @param string $source
     * @return bool
     */
    public function load($source);
    /**
     * @param \Notadd\Foundation\Image\Contracts\Resolver $resolver
     */
    public function process(Resolver $resolver);
    /**
     * @param int $quality
     * @return void
     */
    public function setQuality($quality);
    /**
     * @param string $format
     * @return void
     */
    public function setFileFormat($format);
    /**
     * @return string
     */
    public function getContents();
    /**
     * @return string
     */
    public function getFileFormat();
    /**
     * @return string
     */
    public function getSourceFormat();
    /**
     * @return string
     */
    public function getSourceMimeTime();
    /**
     * @return string
     */
    public function getMimeType();
    /**
     * @return string
     */
    public function getSource();
    /**
     * @return bool
     */
    public function isProcessed();
    /**
     * @return integer
     */
    public function getLastModTime();
    /**
     * @return void
     */
    public function close();
}