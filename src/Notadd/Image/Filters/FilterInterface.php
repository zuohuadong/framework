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
 * Interface FilterInterface
 * @package Notadd\Image\Filters
 */
interface FilterInterface {
    /**\
     * @param  \Notadd\Image\Image $image
     * @return \Notadd\Image\Image
     */
    public function applyFilter(Image $image);
}