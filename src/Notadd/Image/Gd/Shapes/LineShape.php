<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:50
 */
namespace Notadd\Image\Gd\Shapes;
use Notadd\Image\AbstractShape;
use Notadd\Image\Exceptions\NotSupportedException;
use Notadd\Image\Gd\Color;
use Notadd\Image\Image;
/**
 * Class LineShape
 * @package Notadd\Image\Gd\Shapes
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
     * @param integer $x
     * @param integer $y
     */
    public function __construct($x = null, $y = null) {
        $this->x = is_numeric($x) ? intval($x) : $this->x;
        $this->y = is_numeric($y) ? intval($y) : $this->y;
    }
    /**
     * @param  string $color
     * @return void
     */
    public function color($color) {
        $this->color = $color;
    }
    /**
     * @param integer $width
     * @return void
     */
    public function width($width) {
        throw new NotSupportedException("Line width is not supported by GD driver.");
    }
    /**
     * @param  Image $image
     * @param  integer $x
     * @param  integer $y
     * @return boolean
     */
    public function applyToImage(Image $image, $x = 0, $y = 0) {
        $color = new Color($this->color);
        imageline($image->getCore(), $x, $y, $this->x, $this->y, $color->getInt());
        return true;
    }
}