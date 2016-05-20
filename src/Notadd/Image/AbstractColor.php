<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 15:01
 */
namespace Notadd\Image;
use Notadd\Image\Exceptions\NotReadableException;
use Notadd\Image\Exceptions\NotSupportedException;
/**
 * Class AbstractColor
 * @package Notadd\Image
 */
abstract class AbstractColor {
    /**
     * @param  integer $value
     * @return \Notadd\Image\AbstractColor
     */
    abstract public function initFromInteger($value);
    /**
     * @param  array $value
     * @return \Notadd\Image\AbstractColor
     */
    abstract public function initFromArray($value);
    /**
     * @param  string $value
     * @return \Notadd\Image\AbstractColor
     */
    abstract public function initFromString($value);
    /**
     * @param  \ImagickPixel $value
     * @return \Notadd\Image\AbstractColor
     */
    abstract public function initFromObject($value);
    /**
     * @param  integer $r
     * @param  integer $g
     * @param  integer $b
     * @return \Notadd\Image\AbstractColor
     */
    abstract public function initFromRgb($r, $g, $b);
    /**
     * @param  integer $r
     * @param  integer $g
     * @param  integer $b
     * @param  float $a
     * @return \Notadd\Image\AbstractColor
     */
    abstract public function initFromRgba($r, $g, $b, $a);
    /**
     * @return integer
     */
    abstract public function getInt();
    /**
     * @param  string $prefix
     * @return string
     */
    abstract public function getHex($prefix);
    /**
     * @return array
     */
    abstract public function getArray();
    /**
     * @return string
     */
    abstract public function getRgba();
    /**
     * @param  AbstractColor $color
     * @param  integer $tolerance
     * @return boolean
     */
    abstract public function differs(AbstractColor $color, $tolerance = 0);
    /**
     * AbstractColor constructor.
     * @param null $value
     */
    public function __construct($value = null) {
        $this->parse($value);
    }
    /**
     * @param  mixed $value
     * @return \Notadd\Image\AbstractColor
     * @throws \Notadd\Image\Exceptions\NotReadableException
     */
    public function parse($value) {
        switch(true) {
            case is_string($value):
                $this->initFromString($value);
                break;
            case is_int($value):
                $this->initFromInteger($value);
                break;
            case is_array($value):
                $this->initFromArray($value);
                break;
            case is_object($value):
                $this->initFromObject($value);
                break;
            case is_null($value):
                $this->initFromArray(array(
                    255,
                    255,
                    255,
                    0
                ));
                break;
            default:
                throw new NotReadableException("Color format ({$value}) cannot be read.");
        }
        return $this;
    }
    /**
     * @param  string $type
     * @return mixed
     * @throws \Notadd\Image\Exceptions\NotSupportedException
     */
    public function format($type) {
        switch(strtolower($type)) {
            case 'rgba':
                return $this->getRgba();
            case 'hex':
                return $this->getHex('#');
            case 'int':
            case 'integer':
                return $this->getInt();
            case 'array':
                return $this->getArray();
            case 'obj':
            case 'object':
                return $this;
            default:
                throw new NotSupportedException("Color format ({$type}) is not supported.");
        }
    }
    /**
     * @param  string $value
     * @return array
     * @throws \Notadd\Image\Exceptions\NotReadableException
     */
    protected function rgbaFromString($value) {
        $result = false;
        $hexPattern = '/^#?([a-f0-9]{1,2})([a-f0-9]{1,2})([a-f0-9]{1,2})$/i';
        $rgbPattern = '/^rgb ?\(([0-9]{1,3}), ?([0-9]{1,3}), ?([0-9]{1,3})\)$/i';
        $rgbaPattern = '/^rgba ?\(([0-9]{1,3}), ?([0-9]{1,3}), ?([0-9]{1,3}), ?([0-9.]{1,4})\)$/i';
        if(preg_match($hexPattern, $value, $matches)) {
            $result = array();
            $result[0] = strlen($matches[1]) == '1' ? hexdec($matches[1] . $matches[1]) : hexdec($matches[1]);
            $result[1] = strlen($matches[2]) == '1' ? hexdec($matches[2] . $matches[2]) : hexdec($matches[2]);
            $result[2] = strlen($matches[3]) == '1' ? hexdec($matches[3] . $matches[3]) : hexdec($matches[3]);
            $result[3] = 1;
        } elseif(preg_match($rgbPattern, $value, $matches)) {
            $result = array();
            $result[0] = ($matches[1] >= 0 && $matches[1] <= 255) ? intval($matches[1]) : 0;
            $result[1] = ($matches[2] >= 0 && $matches[2] <= 255) ? intval($matches[2]) : 0;
            $result[2] = ($matches[3] >= 0 && $matches[3] <= 255) ? intval($matches[3]) : 0;
            $result[3] = 1;
        } elseif(preg_match($rgbaPattern, $value, $matches)) {
            $result = array();
            $result[0] = ($matches[1] >= 0 && $matches[1] <= 255) ? intval($matches[1]) : 0;
            $result[1] = ($matches[2] >= 0 && $matches[2] <= 255) ? intval($matches[2]) : 0;
            $result[2] = ($matches[3] >= 0 && $matches[3] <= 255) ? intval($matches[3]) : 0;
            $result[3] = ($matches[4] >= 0 && $matches[4] <= 1) ? $matches[4] : 0;
        } else {
            throw new NotReadableException("Unable to read color ({$value}).");
        }
        return $result;
    }
}