<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:45
 */
namespace Notadd\Foundation\Image\Contracts;
/**
 * Interface BinLocator
 * @package Notadd\Foundation\Image\Contracts
 */
interface BinLocator {
    /**
     * @param mixed $path
     * @return mixed
     */
    public function setConverterPath($path);
    /**
     * @return mixed
     */
    public function getConverterPath();
}