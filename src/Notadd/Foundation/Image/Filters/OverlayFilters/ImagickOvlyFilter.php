<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:55
 */
namespace Notadd\Foundation\Image\Filters\OverlayFilters;
use Imagick;
use ImagickPixel;
use Notadd\Foundation\Image\Filters\ImagickFilter;
/**
 * Class ImagickOvlyFilter
 * @package Notadd\Foundation\Image\Filters\OverlayFilters
 */
class ImagickOvlyFilter extends ImagickFilter {
    /**
     * @var array
     */
    protected $availableOptions = [
        'c',
        'a'
    ];
    /**
     * @return void
     */
    public function run() {
        extract($this->driver->getTargetSize());
        $image = $this->driver->getResource();
        $rgba = implode(',', $this->hexToRgb($this->getOption('c', 'fff')));
        $alpha = $this->getOption('a', '0.5');
        $overlay = new Imagick();
        $overlay->newImage($width, $height, new ImagickPixel(sprintf('rgba(%s,%s)', $rgba, $alpha)));
        $image->compositeImage($overlay, Imagick::COMPOSITE_OVER, 0, 0);
        //$this->driver->swapResource($overlay);
    }
}