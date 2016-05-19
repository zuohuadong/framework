<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 15:14
 */
namespace Notadd\Image;
/**
 * Class AbstractShape
 * @package Notadd\Image
 */
abstract class AbstractShape {
    /**
     * @var string
     */
    public $background;
    /**
     * @var string
     */
    public $border_color;
    /**
     * @var integer
     */
    public $border_width = 0;
    /**
     * @param  Image $image
     * @param  integer $posx
     * @param  integer $posy
     * @return boolean
     */
    abstract public function applyToImage(Image $image, $posx = 0, $posy = 0);
    /**
     * @param $color
     */
    public function background($color) {
        $this->background = $color;
    }
    /**
     * @param  integer $width
     * @param  string $color
     * @return void
     */
    public function border($width, $color = null) {
        $this->border_width = is_numeric($width) ? intval($width) : 0;
        $this->border_color = is_null($color) ? '#000000' : $color;
    }
    /**
     * @return boolean
     */
    public function hasBorder() {
        return ($this->border_width >= 1);
    }
}