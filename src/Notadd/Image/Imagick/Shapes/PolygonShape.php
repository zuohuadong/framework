<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:45
 */
namespace Notadd\Image\Imagick\Shapes;
use ImagickDraw;
use Notadd\Image\AbstractShape;
use Notadd\Image\Image;
use Notadd\Image\Imagick\Color;
/**
 * Class PolygonShape
 * @package Notadd\Image\Imagick\Shapes
 */
class PolygonShape extends AbstractShape {
    /**
     * @var array
     */
    public $points;
    /**
     * PolygonShape constructor.
     * @param $points
     */
    public function __construct($points) {
        $this->points = $this->formatPoints($points);
    }
    /**
     * @param  Image $image
     * @param  integer $x
     * @param  integer $y
     * @return boolean
     */
    public function applyToImage(Image $image, $x = 0, $y = 0) {
        $polygon = new ImagickDraw;
        $bgcolor = new Color($this->background);
        $polygon->setFillColor($bgcolor->getPixel());
        if($this->hasBorder()) {
            $border_color = new Color($this->border_color);
            $polygon->setStrokeWidth($this->border_width);
            $polygon->setStrokeColor($border_color->getPixel());
        }
        $polygon->polygon($this->points);
        $image->getCore()->drawImage($polygon);
        return true;
    }
    /**
     * @param $points
     * @return array
     */
    private function formatPoints($points) {
        $ipoints = array();
        $count = 1;
        foreach($points as $key => $value) {
            if($count % 2 === 0) {
                $y = $value;
                $ipoints[] = array(
                    'x' => $x,
                    'y' => $y
                );
            } else {
                $x = $value;
            }
            $count++;
        }
        return $ipoints;
    }
}