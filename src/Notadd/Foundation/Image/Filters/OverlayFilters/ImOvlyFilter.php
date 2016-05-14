<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:56
 */
namespace Notadd\Foundation\Image\Filters\OverlayFilters;
use Notadd\Foundation\Image\Filters\ImFilter;
/**
 * Class ImOvlyFilter
 * @package Notadd\Foundation\Image\Filters\OverlayFilters
 */
class ImOvlyFilter extends ImFilter {
    /**
     * @var array
     */
    protected $availableOptions = [
        'c',
        'a'
    ];
    /**
     * @return array
     */
    public function run() {
        return [
            '( +clone -fill rgba(%s,%s) -colorize 100 ) -compose Over -composite' => [
                implode(',', $this->hexToRgb($this->getOption('c'))),
                $this->getOption('a', '0.5')
            ]
        ];
    }
}