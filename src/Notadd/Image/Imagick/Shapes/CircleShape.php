<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:44
 */
namespace Notadd\Image\Imagick\Shapes;
use Notadd\Image\Image;
/**
 * Class CircleShape
 * @package Notadd\Image\Imagick\Shapes
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
     * @param \Notadd\Image\Image $image
     * @param int $x
     * @param int $y
     * @return bool
     */
    public function applyToImage(Image $image, $x = 0, $y = 0) {
        return parent::applyToImage($image, $x, $y);
    }
}