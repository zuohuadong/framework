<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:47
 */
namespace Notadd\Foundation\Image\Contracts;
/**
 * Interface SourceLoader
 * @package Notadd\Foundation\Image\Contracts
 */
interface SourceLoader {
    /**
     * @param $url
     * @return mixed
     */
    public function load($url);
    /**
     * @return mixed
     */
    public function clean();
    /**
     * @return mixed
     */
    public function getSource();
}