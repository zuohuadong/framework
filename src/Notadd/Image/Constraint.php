<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 15:15
 */
namespace Notadd\Image;
/**
 * Class Constraint
 * @package Notadd\Image
 */
class Constraint {
    const ASPECTRATIO = 1;
    const UPSIZE = 2;
    /**
     * @var \Notadd\Image\Size
     */
    private $size;
    /**
     * @var integer
     */
    private $fixed = 0;
    /**
     * @param Size $size
     */
    public function __construct(Size $size) {
        $this->size = $size;
    }
    /**
     * @return \Notadd\Image\Size
     */
    public function getSize() {
        return $this->size;
    }
    /**
     * @param  integer $type
     * @return void
     */
    public function fix($type) {
        $this->fixed = ($this->fixed & ~(1 << $type)) | (1 << $type);
    }
    /**
     * @param  integer $type
     * @return boolean
     */
    public function isFixed($type) {
        return (bool)($this->fixed & (1 << $type));
    }
    /**
     * @return void
     */
    public function aspectRatio() {
        $this->fix(self::ASPECTRATIO);
    }
    /**
     * @return void
     */
    public function upsize() {
        $this->fix(self::UPSIZE);
    }
}