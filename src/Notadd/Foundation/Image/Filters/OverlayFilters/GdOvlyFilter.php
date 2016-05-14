<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:54
 */
namespace Notadd\Foundation\Image\Filters\OverlayFilters;
use Notadd\Foundation\Image\Filters\GdFilter;
/**
 * Class GdOvlyFilter
 * @package Notadd\Foundation\Image\Filters\OverlayFilters
 */
class GdOvlyFilter extends GdFilter {
    /**
     * @var array
     */
    protected $availableOptions = [
        'c',
        'a'
    ];
    /**
     * @return null
     */
    public function run() {
        list($r, $g, $b) = $this->hexToRgb($this->getOption('c', 'fff'));
        $overlay = $this->createOverlay($r, $g, $b, (float)$this->getOption('a', '0.5'));
        return null;
    }
    /**
     * @param $r
     * @param $g
     * @param $b
     * @param $alpha
     */
    private function createOverlay($r, $g, $b, $alpha) {
        extract($this->driver->getTargetSize());
        $image = $this->driver->getResource();
        imagealphablending($image, true);
        $alpha = (int)(127 * 0.5);
        imagefilledrectangle($image, 0, 0, $width, $height, imagecolorallocatealpha($image, $r, $g, $b, $alpha));
    }
}