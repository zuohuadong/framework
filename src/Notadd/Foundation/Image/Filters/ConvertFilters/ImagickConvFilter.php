<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:49
 */
namespace Notadd\Foundation\Image\Filters\ConvertFilters;
use Notadd\Foundation\Image\Filters\ImagickFilter;
/**
 * Class ImagickConvFilter
 * @package Notadd\Foundation\Image\Filters\ConvertFilters
 */
class ImagickConvFilter extends ImagickFilter {
    /**
     * @var array
     */
    protected $availableOptions = ['f'];
    /**
     * @return void
     */
    public function run() {
        $type = $this->getOption('f', 'jpg');
        $this->driver->setOutputType($type);
    }
}