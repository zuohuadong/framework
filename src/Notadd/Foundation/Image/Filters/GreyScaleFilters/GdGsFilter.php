<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:51
 */
namespace Notadd\Foundation\Image\Filters\GreyScaleFilters;
use Notadd\Foundation\Image\Filters\GdFilter;
/**
 * Class GdGsFilter
 * @package Notadd\Foundation\Image\Filters\GreyScaleFilters
 */
class GdGsFilter extends GdFilter {
    /**
     * @return void
     */
    public function run() {
        imagefilter($this->driver->getResource(), IMG_FILTER_GRAYSCALE);
    }
}