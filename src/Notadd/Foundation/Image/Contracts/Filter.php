<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:48
 */
namespace Notadd\Foundation\Image\Contracts;
/**
 * Interface Filter
 * @package Notadd\Foundation\Image\Contracts
 */
interface Filter {
    /**
     * Filter constructor.
     * @param \Notadd\Foundation\Image\Contracts\Driver $driver
     * @param $options
     */
    public function __construct(Driver $driver, $options);
    /**
     * @return void
     */
    public function run();
    /**
     * @param string $option
     * @param mixed $default
     * @return mixed
     */
    public function getOption($option, $default = null);
}