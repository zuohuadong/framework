<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 15:32
 */
namespace Notadd\Image;
/**
 * Class Point
 * @package Notadd\Image
 */
class Point {
    /**
     * @var integer
     */
    public $x;
    /**
     * @var integer
     */
    public $y;
    /**
     * @param integer $x
     * @param integer $y
     */
    public function __construct($x = null, $y = null) {
        $this->x = is_numeric($x) ? intval($x) : 0;
        $this->y = is_numeric($y) ? intval($y) : 0;
    }
    /**
     * @param integer $x
     */
    public function setX($x) {
        $this->x = intval($x);
    }
    /**
     * @param integer $y
     */
    public function setY($y) {
        $this->y = intval($y);
    }
    /**
     * @param integer $x
     * @param integer $y
     */
    public function setPosition($x, $y) {
        $this->setX($x);
        $this->setY($y);
    }
}