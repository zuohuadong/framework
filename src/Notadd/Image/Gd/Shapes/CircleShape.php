<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:49
 */
namespace Notadd\Image\Gd\Shapes;
use Notadd\Image\Image;
/**
 * Class CircleShape
 * @package Notadd\Image\Gd\Shapes
 */
class CircleShape extends EllipseShape {
    /**
     * @var integer
     */
    public $diameter = 100;
    /**
     * @param integer $diameter
     */
    public function __construct($diameter = null) {
        $this->width = is_numeric($diameter) ? intval($diameter) : $this->diameter;
        $this->height = is_numeric($diameter) ? intval($diameter) : $this->diameter;
        $this->diameter = is_numeric($diameter) ? intval($diameter) : $this->diameter;
    }
    /**
     * @param  Image $image
     * @param  integer $x
     * @param  integer $y
     * @return boolean
     */
    public function applyToImage(Image $image, $x = 0, $y = 0) {
        return parent::applyToImage($image, $x, $y);
    }
}