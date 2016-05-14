<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:51
 */
namespace Notadd\Foundation\Image\Filters\GreyScaleFilters;
use Notadd\Foundation\Image\Filters\ImagickFilter;
/**
 * Class ImagickGsFilter
 * @package Notadd\Foundation\Image\Filters\GreyScaleFilters
 */
class ImagickGsFilter extends ImagickFilter {
    /**
     * @var array
     */
    protected $availableOptions = [
        'h',
        's',
        'b',
        'c'
    ];
    /**
     * @return void
     */
    public function run() {
        $this->driver->getResource()->modulateImage((int)$this->getOption('b', 100), (int)$this->getOption('s', 0), (int)$this->getOption('h', 100));
        $this->driver->getResource()->contrastImage((bool)$this->getOption('c', 1));
    }
}