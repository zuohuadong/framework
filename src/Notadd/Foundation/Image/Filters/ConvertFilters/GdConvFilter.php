<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:48
 */
namespace Notadd\Foundation\Image\Filters\ConvertFilters;
/**
 * Class GdConvFilter
 * @package Notadd\Foundation\Image\Filters\ConvertFilters
 */
class GdConvFilter extends ImagickConvFilter {
    /**
     * @var string
     */
    protected static $driverType = 'gd';
}