<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:44
 */
namespace Notadd\Image\Imagick\Shapes;
use Notadd\Image\AbstractShape;
use Notadd\Image\Image;
use Notadd\Image\Imagick\Color;
/**
 * Class LineShape
 * @package Notadd\Image\Imagick\Shapes
 */
class LineShape extends AbstractShape {
    /**
     * @var integer
     */
    public $x = 0;
    /**
     * @var integer
     */
    public $y = 0;
    /**
     * @var string
     */
    public $color = '#000000';
    /**
     * @var integer
     */
    public $width = 1;
    /**
     * LineShape constructor.
     * @param null $x
     * @param null $y
     */
    public function __construct($x = null, $y = null) {
        $this->x = is_numeric($x) ? intval($x) : $this->x;
        $this->y = is_numeric($y) ? intval($y) : $this->y;
    }
    /**
     * @param $color
     */
    public function color($color) {
        $this->color = $color;
    }
    /**
     * @param $width
     */
    public function width($width) {
        $this->width = $width;
    }
    /**
     * @param \Notadd\Image\Image $image
     * @param int $x
     * @param int $y
     * @return bool
     */
    public function applyToImage(Image $image, $x = 0, $y = 0) {
        $line = new \ImagickDraw;
        $color = new Color($this->color);
        $line->setStrokeColor($color->getPixel());
        $line->setStrokeWidth($this->width);
        $line->line($this->x, $this->y, $x, $y);
        $image->getCore()->drawImage($line);
        return true;
    }
}