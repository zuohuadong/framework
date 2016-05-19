<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:52
 */
namespace Notadd\Image\Gd\Shapes;
use Notadd\Image\AbstractShape;
use Notadd\Image\Gd\Color;
use Notadd\Image\Image;
/**
 * Class RectangleShape
 * @package Notadd\Image\Gd\Shapes
 */
class RectangleShape extends AbstractShape {
    /**
     * @var integer
     */
    public $x1 = 0;
    /**
     * @var integer
     */
    public $y1 = 0;
    /**
     * @var integer
     */
    public $x2 = 0;
    /**
     * @var integer
     */
    public $y2 = 0;
    /**
     * @param integer $x1
     * @param integer $y1
     * @param integer $x2
     * @param integer $y2
     */
    public function __construct($x1 = null, $y1 = null, $x2 = null, $y2 = null) {
        $this->x1 = is_numeric($x1) ? intval($x1) : $this->x1;
        $this->y1 = is_numeric($y1) ? intval($y1) : $this->y1;
        $this->x2 = is_numeric($x2) ? intval($x2) : $this->x2;
        $this->y2 = is_numeric($y2) ? intval($y2) : $this->y2;
    }
    /**
     * @param  Image $image
     * @param  integer $x
     * @param  integer $y
     * @return boolean
     */
    public function applyToImage(Image $image, $x = 0, $y = 0) {
        $background = new Color($this->background);
        imagefilledrectangle($image->getCore(), $this->x1, $this->y1, $this->x2, $this->y2, $background->getInt());
        if($this->hasBorder()) {
            $border_color = new Color($this->border_color);
            imagesetthickness($image->getCore(), $this->border_width);
            imagerectangle($image->getCore(), $this->x1, $this->y1, $this->x2, $this->y2, $border_color->getInt());
        }
        return true;
    }
}