<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:51
 */
namespace Notadd\Image\Gd\Shapes;
use Notadd\Image\AbstractShape;
use Notadd\Image\Gd\Color;
use Notadd\Image\Image;
/**
 * Class PolygonShape
 * @package Notadd\Image\Gd\Shapes
 */
class PolygonShape extends AbstractShape {
    /**
     * @var integer
     */
    public $points;
    /**
     * @param array $points
     */
    public function __construct($points) {
        $this->points = $points;
    }
    /**
     * @param  Image $image
     * @param  integer $x
     * @param  integer $y
     * @return boolean
     */
    public function applyToImage(Image $image, $x = 0, $y = 0) {
        $background = new Color($this->background);
        imagefilledpolygon($image->getCore(), $this->points, intval(count($this->points) / 2), $background->getInt());
        if($this->hasBorder()) {
            $border_color = new Color($this->border_color);
            imagesetthickness($image->getCore(), $this->border_width);
            imagepolygon($image->getCore(), $this->points, intval(count($this->points) / 2), $border_color->getInt());
        }
        return true;
    }
}