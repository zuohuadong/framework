<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:39
 */
namespace Notadd\Image\Gd\Shapes;
use Notadd\Image\AbstractShape;
use Notadd\Image\Gd\Color;
use Notadd\Image\Image;
/**
 * Class EllipseShape
 * @package Notadd\Image\Gd\Shapes
 */
class EllipseShape extends AbstractShape {
    /**
     * @var integer
     */
    public $width = 100;
    /**
     * @var integer
     */
    public $height = 100;
    /**
     * @param integer $width
     * @param integer $height
     */
    public function __construct($width = null, $height = null) {
        $this->width = is_numeric($width) ? intval($width) : $this->width;
        $this->height = is_numeric($height) ? intval($height) : $this->height;
    }
    /**
     * @param  Image $image
     * @param  integer $x
     * @param  integer $y
     * @return boolean
     */
    public function applyToImage(Image $image, $x = 0, $y = 0) {
        $background = new Color($this->background);
        if($this->hasBorder()) {
            imagefilledellipse($image->getCore(), $x, $y, $this->width - 1, $this->height - 1, $background->getInt());
            $border_color = new Color($this->border_color);
            imagesetthickness($image->getCore(), $this->border_width);
            imagearc($image->getCore(), $x, $y, $this->width, $this->height, 0, 359.99, $border_color->getInt());
        } else {
            imagefilledellipse($image->getCore(), $x, $y, $this->width, $this->height, $background->getInt());
        }
        return true;
    }
}