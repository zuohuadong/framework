<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:41
 */
namespace Notadd\Image\Imagick\Shapes;
use ImagickDraw;
use Notadd\Image\AbstractShape;
use Notadd\Image\Image;
use Notadd\Image\Imagick\Color;
/**
 * Class EllipseShape
 * @package Notadd\Image\Imagick\Shapes
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
     * @param \Notadd\Image\Image $image
     * @param int $x
     * @param int $y
     * @return bool
     */
    public function applyToImage(Image $image, $x = 0, $y = 0) {
        $circle = new ImagickDraw;
        $bgcolor = new Color($this->background);
        $circle->setFillColor($bgcolor->getPixel());
        if($this->hasBorder()) {
            $border_color = new Color($this->border_color);
            $circle->setStrokeWidth($this->border_width);
            $circle->setStrokeColor($border_color->getPixel());
        }
        $circle->ellipse($x, $y, $this->width / 2, $this->height / 2, 0, 360);
        $image->getCore()->drawImage($circle);
        return true;
    }
}