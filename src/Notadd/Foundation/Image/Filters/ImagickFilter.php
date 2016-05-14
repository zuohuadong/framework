<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:37
 */
namespace Notadd\Foundation\Image\Filters;
/**
 * Class ImagickFilter
 * @package Notadd\Foundation\Image\Filters
 */
abstract class ImagickFilter extends AbstractFilter {
    /**
     * @var string
     */
    protected static $driverType = 'imagick';
}