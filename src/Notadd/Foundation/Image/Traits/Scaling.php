<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:58
 */
namespace Notadd\Foundation\Image\Traits;
/**
 * Class Scaling
 * @package Notadd\Foundation\Image\Traits
 */
trait Scaling {
    /**
     * @param int $w maximum width
     * @param int $h maximum height
     * @param int $width
     * @param int $height
     * @return mixed
     */
    protected function fitInBounds($w, $h, $width, $height) {
        if($width >= $w or $height >= $h) {
            $ratA = $this->ratio($width, $height);
            $ratM = min($ratA, $this->ratio($w, $h));
            $isR = $ratM === $ratA;
            $valW = (int)round($h * $ratM);
            $valH = (int)round($w / $ratA);
            list($width, $height) = $width <= $height ? [
                $valW,
                $isR ? $h : $valH
            ] : [
                $isR ? $w : $valW,
                $valH
            ];
        }
        return compact('width', 'height');
    }
    /**
     * @param int $w
     * @param int $h
     * @param int $width
     * @param int $height
     * @return void
     */
    protected function fillArea(&$w, &$h, $width, $height) {
        extract($this->getInfo());
        $ratio = $this->ratio($width, $height);
        $minW = min($w, $width);
        $minH = min($h, $height);
        $minB = min($w, $h);
        if($minB === 0 or ($minW > $width and $minH > $height)) {
            return;
        }
        $ratioC = $this->ratio($w, $h);
        list($w, $h) = $ratio > $ratioC ? [
            (int)ceil($h * $ratio),
            $h
        ] : [
            $w,
            (int)ceil($w / $ratio)
        ];
    }
    /**
     * @param int $width
     * @param int $height
     * @return float
     */
    protected function ratio($width, $height) {
        return (float)($width / $height);
    }
    /**
     * @param int $width
     * @param int $height
     * @param int $limit
     * @return array $width and $height
     */
    protected function pixelLimit($width, $height, $limit) {
        $ratio = $this->ratio($width, $height);
        $width = (int)round(sqrt($limit * $ratio));
        $height = (int)floor($width / $ratio);
        return compact('width', 'height');
    }
    /**
     * @param int $width
     * @param int $height
     * @param $percent
     * @return array $width and $height
     */
    protected function percentualScale($width, $height, $percent) {
        $ratio = $this->ratio($width, $height);
        $width = (int)(round($width * $percent) / 100);
        $height = (int)floor($width / $ratio);
        return compact('width', 'height');
    }
    /**
     * @param int $width
     * @param int $height
     * @param int $w
     * @param int $h
     * @param int $gravity
     * @return array
     */
    protected function getCropCoordinates($width, $height, $w, $h, $gravity) {
        $x = $y = 0;
        switch($gravity) {
            case 1:
                break;
            case 3:
                $x = ($width) - $w;
                break;
            case 2:
                $x = ($width / 2) - ($w / 2);
                break;
            case 4:
                $y = ($height / 2) - ($h / 2);
                break;
            case 5:
                $x = ($width / 2) - ($w / 2);
                $y = $height / 2 - ($h / 2);
                break;
            case 6:
                $x = $width - $w;
                $y = ($height / 2) - ($h / 2);
                break;
            case 7:
                $x = 0;
                $y = $height - $h;
                break;
            case 8:
                $x = ($width / 2) - ($w / 2);
                $y = $height - $h;
                break;
            case 9:
                $x = $width - $w;
                $y = $height - $h;
                break;
        }
        $x = (int)ceil($x);
        $y = (int)ceil($y);
        return compact('x', 'y');
    }
}