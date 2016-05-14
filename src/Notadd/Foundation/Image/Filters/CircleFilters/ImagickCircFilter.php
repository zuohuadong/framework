<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:41
 */
namespace Notadd\Foundation\Image\Filters\CircleFilters;
use Imagick;
use ImagickDraw;
use Notadd\Foundation\Image\Filters\ImagickFilter;
/**
 * Class ImagickCircFilter
 * @package Notadd\Foundation\Image\Filters\CircleFilters
 */
class ImagickCircFilter extends ImagickFilter {
    /**
     * @var array
     */
    protected $availableOptions = ['o'];
    /**
     * @return void
     */
    public function run() {
        extract($this->driver->getTargetSize());
        $image = $this->driver->getResource();
        $mask = new Imagick();
        $mask->newImage($width, $height, 'transparent');
        $mask->thresholdImage(-1);
        $mask->negateImage(1);
        $mask->setImageMatte(1);
        $circle = $this->makeCircle($width, $height);
        $mask->drawImage($circle);
        $mask->gammaImage(2.2);
        $image->setImageMatte(1);
        $image->setImageBackgroundColor('white');
        $image->compositeImage($mask, Imagick::COMPOSITE_DSTIN, 0, 0);
        $image->setImageFormat('gif' === $this->driver->getOutputType() ? 'gif' : 'png');
    }
    /**
     * @param mixed $width
     * @param mixed $height
     * @return mixed
     */
    protected function getCoordinates($width, $height) {
        $max = (int)ceil(max($width, $height) / 2);
        $min = (int)ceil(min($width, $height) / 2);
        return $width > $height ? [
            $max,
            $min,
            $max,
            $this->getOption('o', 1)
        ] : [
            $min,
            $max,
            $this->getOption('o', 1),
            $max
        ];
    }
    /**
     * @param mixed $width
     * @param mixed $height
     * @return mixed
     */
    protected function makeCircle($width, $height) {
        list($ox, $oy, $px, $py) = $this->getCoordinates($width, $height);
        $circle = new ImagickDraw();
        $circle->setFillColor('white');
        $circle->circle($ox, $oy, $px, $py);
        return $circle;
    }
}