<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:46
 */
namespace Notadd\Image\Imagick\Shapes;
use ImagickDraw;
use Notadd\Image\AbstractShape;
use Notadd\Image\Image;
use Notadd\Image\Imagick\Color;
/**
 * Class RectangleShape
 * @package Notadd\Image\Imagick\Shapes
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
     * RectangleShape constructor.
     * @param null $x1
     * @param null $y1
     * @param null $x2
     * @param null $y2
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
        $rectangle = new ImagickDraw;
        $bgcolor = new Color($this->background);
        $rectangle->setFillColor($bgcolor->getPixel());
        if($this->hasBorder()) {
            $border_color = new Color($this->border_color);
            $rectangle->setStrokeWidth($this->border_width);
            $rectangle->setStrokeColor($border_color->getPixel());
        }
        $rectangle->rectangle($this->x1, $this->y1, $this->x2, $this->y2);
        $image->getCore()->drawImage($rectangle);
        return true;
    }
}