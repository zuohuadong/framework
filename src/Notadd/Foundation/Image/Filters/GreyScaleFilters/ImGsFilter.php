<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:53
 */
namespace Notadd\Foundation\Image\Filters\GreyScaleFilters;
use Notadd\Foundation\Image\Filters\ImFilter;
/**
 * Class ImGsFilter
 * @package Notadd\Foundation\Image\Filters\GreyScaleFilters
 */
class ImGsFilter extends ImFilter {
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
     * @return array
     */
    public function run() {
        return [
            '-modulate %s,%s,%s' => [
                (int)$this->getOption('b', 100),
                (int)$this->getOption('s', 0),
                (int)$this->getOption('h', 100)
            ],
            '%scontrast' => [false !== (bool)$this->getOption('c', 1) ? '-' : '+'],
        ];
    }
}