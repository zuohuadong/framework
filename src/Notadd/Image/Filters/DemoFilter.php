<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:07
 */
namespace Notadd\Image\Filters;
use Notadd\Image\Image;
/**
 * Class DemoFilter
 * @package Notadd\Image\Filters
 */
class DemoFilter implements FilterInterface {
    const DEFAULT_SIZE = 10;
    /**
     * @var integer
     */
    private $size;
    /**
     * @param integer $size
     */
    public function __construct($size = null) {
        $this->size = is_numeric($size) ? intval($size) : self::DEFAULT_SIZE;
    }
    /**
     * @param  \Notadd\Image\Image $image
     * @return \Notadd\Image\Image
     */
    public function applyFilter(Image $image) {
        $image->pixelate($this->size);
        $image->greyscale();
        return $image;
    }
}