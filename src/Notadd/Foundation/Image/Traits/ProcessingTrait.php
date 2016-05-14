<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:56
 */
namespace Notadd\Foundation\Image\Traits;
use InvalidArgumentException;
/**
 * Class ProcessingTrait
 * @package Notadd\Foundation\Image\Traits
 */
trait ProcessingTrait {
    /**
     * @param $hex
     * @return array
     */
    public function hexToRgb($hex) {
        if(3 === ($len = strlen($hex))) {
            $rgb = str_split($hex);
            list($r, $g, $b) = $rgb;
            $rgb = [
                hexdec($r . $r),
                hexdec($g . $g),
                hexdec($b . $b)
            ];
        } elseif(6 === $len) {
            $rgb = str_split($hex, 2);
            list($r, $g, $b) = $rgb;
            $rgb = [
                hexdec($r),
                hexdec($g),
                hexdec($b)
            ];
        } else {
            throw new InvalidArgumentException(sprintf('invalid hex value %s', $hex));
        }
        return $rgb;
    }
}