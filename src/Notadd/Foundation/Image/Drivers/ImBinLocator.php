<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 12:50
 */
namespace Notadd\Foundation\Image\Drivers;
use Notadd\Foundation\Image\Contracts\BinLocator;
/**
 * Class ImBinLocator
 * @package Notadd\Foundation\Image\Drivers
 */
class ImBinLocator implements BinLocator {
    /**
     * @var string
     */
    protected $path;
    /**
     * @param mixed $path
     * @return mixed
     */
    public function setConverterPath($path) {
        return $this->path = $path;
    }
    /**
     * @return mixed
     */
    public function getConverterPath() {
        return $this->path;
    }
}