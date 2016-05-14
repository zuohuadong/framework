<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:42
 */
namespace Notadd\Foundation\Image\Filters\CircleFilters;
use Notadd\Foundation\Image\Filters\ImFilter;
/**
 * Class ImCircFilter
 * @package Notadd\Foundation\Image\Filters\CircleFilters
 */
class ImCircFilter extends ImFilter {
    /**
     * @var array
     */
    protected $availableOptions = ['o'];
    /**
     * @return array
     */
    public function run() {
        $this->driver->setOutPutType('png');
        extract($this->driver->getTargetSize());
        return [
            '( +clone -threshold -1 -negate -fill white -draw "circle %s,%s %s,%s" -gamma 2.2 ) -alpha Off -compose CopyOpacity -composite' => $this->getCoordinates($width, $height)
        ];
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
}