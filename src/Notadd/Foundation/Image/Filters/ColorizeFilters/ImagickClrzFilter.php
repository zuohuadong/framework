<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:44
 */
namespace Notadd\Foundation\Image\Filters\ColorizeFilters;
use Imagick;
use ImagickPixel;
use Notadd\Foundation\Image\Filters\ImagickFilter;
/**
 * Class ImagickClrzFilter
 * @package Notadd\Foundation\Image\Filters\ColorizeFilters
 */
class ImagickClrzFilter extends ImagickFilter {
    /**
     * @var array
     */
    protected $availableOptions = ['c'];
    /**
     * @return void
     */
    public function run() {
        extract($this->driver->getTargetSize());
        $image = $this->driver->getResource();
        $rgba = implode(',', $this->hexToRgb($this->getOption('c', 'fff')));
        $overlay = new Imagick();
        $overlay->newImage($width, $height, new ImagickPixel(sprintf('rgb(%s)', $rgba)));
        $image->compositeImage($overlay, Imagick::COMPOSITE_COLORIZE, 0, 0);
    }
}