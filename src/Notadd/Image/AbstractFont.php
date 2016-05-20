<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 15:13
 */
namespace Notadd\Image;
/**
 * Class AbstractFont
 * @package Notadd\Image
 */
abstract class AbstractFont {
    /**
     * @var String
     */
    public $text;
    /**
     * @var integer
     */
    public $size = 12;
    /**
     * @var mixed
     */
    public $color = '000000';
    /**
     * @var integer
     */
    public $angle = 0;
    /**
     * @var String
     */
    public $align;
    /**
     * @var String
     */
    public $valign;
    /**
     * @var mixed
     */
    public $file;
    /**
     * @param  Image $image
     * @param  integer $posx
     * @param  integer $posy
     * @return boolean
     */
    abstract public function applyToImage(Image $image, $posx = 0, $posy = 0);
    /**
     * AbstractFont constructor.
     * @param null $text
     */
    public function __construct($text = null) {
        $this->text = $text;
    }
    /**
     * @param  String $text
     * @return void
     */
    public function text($text) {
        $this->text = $text;
    }
    /**
     * @return String
     */
    public function getText() {
        return $this->text;
    }
    /**
     * @param  integer $size
     * @return void
     */
    public function size($size) {
        $this->size = $size;
    }
    /**
     * @return integer
     */
    public function getSize() {
        return $this->size;
    }
    /**
     * @param  mixed $color
     * @return void
     */
    public function color($color) {
        $this->color = $color;
    }
    /**
     * @return mixed
     */
    public function getColor() {
        return $this->color;
    }
    /**
     * @param  integer $angle
     * @return void
     */
    public function angle($angle) {
        $this->angle = $angle;
    }
    /**
     * @return integer
     */
    public function getAngle() {
        return $this->angle;
    }
    /**
     * @param  string $align
     * @return void
     */
    public function align($align) {
        $this->align = $align;
    }
    /**
     * @return string
     */
    public function getAlign() {
        return $this->align;
    }
    /**
     * @param  string $valign
     * @return void
     */
    public function valign($valign) {
        $this->valign = $valign;
    }
    /**
     * @return string
     */
    public function getValign() {
        return $this->valign;
    }
    /**
     * @param  string $file
     * @return void
     */
    public function file($file) {
        $this->file = $file;
    }
    /**
     * @return string
     */
    public function getFile() {
        return $this->file;
    }
    /**
     * @return boolean
     */
    protected function hasApplicableFontFile() {
        if(is_string($this->file)) {
            return file_exists($this->file);
        }
        return false;
    }
    /**
     * @return integer
     */
    public function countLines() {
        return count(explode(PHP_EOL, $this->text));
    }
}