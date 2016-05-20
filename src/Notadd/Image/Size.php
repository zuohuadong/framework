<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 15:39
 */
namespace Notadd\Image;
use Closure;
use Notadd\Image\Exceptions\InvalidArgumentException;
/**
 * Class Size
 * @package Notadd\Image
 */
class Size {
    /**
     * @var integer
     */
    public $width;
    /**
     * @var integer
     */
    public $height;
    /**
     * @var Point
     */
    public $pivot;
    /**
     * @param integer $width
     * @param integer $height
     * @param Point $pivot
     */
    public function __construct($width = null, $height = null, Point $pivot = null) {
        $this->width = is_numeric($width) ? intval($width) : 1;
        $this->height = is_numeric($height) ? intval($height) : 1;
        $this->pivot = $pivot ? $pivot : new Point;
    }
    /**
     * @param integer $width
     * @param integer $height
     */
    public function set($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }
    /**
     * @param Point $point
     */
    public function setPivot(Point $point) {
        $this->pivot = $point;
    }
    /**
     * @return integer
     */
    public function getWidth() {
        return $this->width;
    }
    /**
     * @return integer
     */
    public function getHeight() {
        return $this->height;
    }
    /**
     * @return float
     */
    public function getRatio() {
        return $this->width / $this->height;
    }
    /**
     * @param  integer $width
     * @param  integer $height
     * @param  Closure $callback
     * @return \Notadd\Image\Size
     * @throws InvalidArgumentException
     */
    public function resize($width, $height, Closure $callback = null) {
        if(is_null($width) && is_null($height)) {
            throw new InvalidArgumentException("Width or height needs to be defined.");
        }
        $dominant_w_size = clone $this;
        $dominant_w_size->resizeHeight($height, $callback);
        $dominant_w_size->resizeWidth($width, $callback);
        $dominant_h_size = clone $this;
        $dominant_h_size->resizeWidth($width, $callback);
        $dominant_h_size->resizeHeight($height, $callback);
        if($dominant_h_size->fitsInto(new self($width, $height))) {
            $this->set($dominant_h_size->width, $dominant_h_size->height);
        } else {
            $this->set($dominant_w_size->width, $dominant_w_size->height);
        }
        return $this;
    }
    /**
     * @param  integer $width
     * @param  Closure $callback
     * @return Size
     */
    private function resizeWidth($width, Closure $callback = null) {
        $constraint = $this->getConstraint($callback);
        if($constraint->isFixed(Constraint::UPSIZE)) {
            $max_width = $constraint->getSize()->getWidth();
            $max_height = $constraint->getSize()->getHeight();
        }
        if(is_numeric($width)) {
            if($constraint->isFixed(Constraint::UPSIZE)) {
                $this->width = ($width > $max_width) ? $max_width : $width;
            } else {
                $this->width = $width;
            }
            if($constraint->isFixed(Constraint::ASPECTRATIO)) {
                $h = intval(round($this->width / $constraint->getSize()->getRatio()));
                if($constraint->isFixed(Constraint::UPSIZE)) {
                    $this->height = ($h > $max_height) ? $max_height : $h;
                } else {
                    $this->height = $h;
                }
            }
        }
    }
    /**
     * @param  integer $height
     * @param  Closure $callback
     * @return Size
     */
    private function resizeHeight($height, Closure $callback = null) {
        $constraint = $this->getConstraint($callback);
        if($constraint->isFixed(Constraint::UPSIZE)) {
            $max_width = $constraint->getSize()->getWidth();
            $max_height = $constraint->getSize()->getHeight();
        }
        if(is_numeric($height)) {
            if($constraint->isFixed(Constraint::UPSIZE)) {
                $this->height = ($height > $max_height) ? $max_height : $height;
            } else {
                $this->height = $height;
            }
            if($constraint->isFixed(Constraint::ASPECTRATIO)) {
                $w = intval(round($this->height * $constraint->getSize()->getRatio()));
                if($constraint->isFixed(Constraint::UPSIZE)) {
                    $this->width = ($w > $max_width) ? $max_width : $w;
                } else {
                    $this->width = $w;
                }
            }
        }
    }
    /**
     * @param  Size $size
     * @return \Notadd\Image\Point
     */
    public function relativePosition(Size $size) {
        $x = $this->pivot->x - $size->pivot->x;
        $y = $this->pivot->y - $size->pivot->y;
        return new Point($x, $y);
    }
    /**
     * @param  Size $size
     * @param string $position
     * @return \Notadd\Image\Size
     * @throws InvalidArgumentException
     */
    public function fit(Size $size, $position = 'center') {
        $auto_height = clone $size;
        $auto_height->resize($this->width, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        if($auto_height->fitsInto($this)) {
            $size = $auto_height;
        } else {
            $auto_width = clone $size;
            $auto_width->resize(null, $this->height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $size = $auto_width;
        }
        $this->align($position);
        $size->align($position);
        $size->setPivot($this->relativePosition($size));
        return $size;
    }
    /**
     * @param  Size $size
     * @return boolean
     */
    public function fitsInto(Size $size) {
        return ($this->width <= $size->width) && ($this->height <= $size->height);
    }
    /**
     * @param  string $position
     * @param  integer $offset_x
     * @param  integer $offset_y
     * @return \Notadd\Image\Size
     */
    public function align($position, $offset_x = 0, $offset_y = 0) {
        switch(strtolower($position)) {
            case 'top':
            case 'top-center':
            case 'top-middle':
            case 'center-top':
            case 'middle-top':
                $x = intval($this->width / 2);
                $y = 0 + $offset_y;
                break;
            case 'top-right':
            case 'right-top':
                $x = $this->width - $offset_x;
                $y = 0 + $offset_y;
                break;
            case 'left':
            case 'left-center':
            case 'left-middle':
            case 'center-left':
            case 'middle-left':
                $x = 0 + $offset_x;
                $y = intval($this->height / 2);
                break;
            case 'right':
            case 'right-center':
            case 'right-middle':
            case 'center-right':
            case 'middle-right':
                $x = $this->width - $offset_x;
                $y = intval($this->height / 2);
                break;
            case 'bottom-left':
            case 'left-bottom':
                $x = 0 + $offset_x;
                $y = $this->height - $offset_y;
                break;
            case 'bottom':
            case 'bottom-center':
            case 'bottom-middle':
            case 'center-bottom':
            case 'middle-bottom':
                $x = intval($this->width / 2);
                $y = $this->height - $offset_y;
                break;
            case 'bottom-right':
            case 'right-bottom':
                $x = $this->width - $offset_x;
                $y = $this->height - $offset_y;
                break;
            case 'center':
            case 'middle':
            case 'center-center':
            case 'middle-middle':
                $x = intval($this->width / 2);
                $y = intval($this->height / 2);
                break;
            default:
            case 'top-left':
            case 'left-top':
                $x = 0 + $offset_x;
                $y = 0 + $offset_y;
                break;
        }
        $this->pivot->setPosition($x, $y);
        return $this;
    }
    /**
     * @param  Closure $callback
     * @return \Notadd\Image\Constraint
     */
    private function getConstraint(Closure $callback = null) {
        $constraint = new Constraint(clone $this);
        if(is_callable($callback)) {
            $callback($constraint);
        }
        return $constraint;
    }
}