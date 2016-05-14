<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:44
 */
namespace Notadd\Foundation\Image\Filters\ColorizeFilters;
use Notadd\Foundation\Image\Filters\GdFilter;
/**
 * Class GdClrzFilter
 * @package Notadd\Foundation\Image\Filters\ColorizeFilters
 */
class GdClrzFilter extends GdFilter {
    /**
     * @var array
     */
    protected $availableOptions = ['c'];
    /**
     * @return void
     */
    public function run() {
        list($r, $g, $b) = $this->hexToRgb($this->getOption('c', 'fff'));
        imagefilter($this->driver->getResource(), IMG_FILTER_CONTRAST, 1);
        imagefilter($this->driver->getResource(), IMG_FILTER_BRIGHTNESS, -12);
        imagefilter($this->driver->getResource(), IMG_FILTER_GRAYSCALE);
        $this->getOverlay($r, $g, $b);
    }
    /**
     * @param $r
     * @param $g
     * @param $b
     */
    protected function getOverlay($r, $g, $b) {
        extract($this->driver->getTargetSize());
        $image = $this->driver->getResource();
        $overlay = imagecreatetruecolor($width, $height);
        imagealphablending($image, true);
        imagelayereffect($image, IMG_EFFECT_OVERLAY);
        imagefilledrectangle($overlay, 0, 0, $width, $height, imagecolorallocatealpha($overlay, $r, $g, $b, 0));
        imagecopy($image, $overlay, 0, 0, 0, 0, imagesx($overlay), imagesy($overlay));
    }
}