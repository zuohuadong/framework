<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:47
 */
namespace Notadd\Foundation\Image\Filters\ColorizeFilters;
use Notadd\Foundation\Image\Filters\ImFilter;
/**
 * Class ImClrzFilter
 * @package Notadd\Foundation\Image\Filters\ColorizeFilters
 */
class ImClrzFilter extends ImFilter {
    /**
     * @var array
     */
    protected $availableOptions = ['c'];
    /**
     * @return array
     */
    public function run() {
        return [
            '( +clone -fill rgb(%s) -colorize 100 ) -compose Colorize -composite' => [
                implode(',', $this->hexToRgb($this->getOption('c')))
            ]
        ];
    }
}