<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:53
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
use Notadd\Image\Exceptions\NotReadableException;
use Notadd\Image\Image;
use Notadd\Image\Imagick\Color;
use Notadd\Image\Imagick\Decoder;
/**
 * Class FillCommand
 * @package Notadd\Image\Imagick\Commands
 */
class FillCommand extends AbstractCommand {
    /**
     * Fills image with color or pattern
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $filling = $this->argument(0)->value();
        $x = $this->argument(1)->type('digit')->value();
        $y = $this->argument(2)->type('digit')->value();
        $imagick = $image->getCore();
        try {
            $source = new Decoder;
            $filling = $source->init($filling);
        } catch(NotReadableException $e) {
            $filling = new Color($filling);
        }
        if(is_int($x) && is_int($y)) {
            if($filling instanceof Image) {
                $tile = clone $image->getCore();
                $tile->transparentPaintImage($tile->getImagePixelColor($x, $y), 0, 0, false);
                $canvas = clone $image->getCore();
                $canvas = $canvas->textureImage($filling->getCore());
                $canvas->compositeImage($tile, \Imagick::COMPOSITE_DEFAULT, 0, 0);
                $image->setCore($canvas);
            } elseif($filling instanceof Color) {
                $canvas = new \Imagick;
                $canvas->newImage($image->getWidth(), $image->getHeight(), $filling->getPixel(), 'png');
                $tile = clone $image->getCore();
                $tile->transparentPaintImage($tile->getImagePixelColor($x, $y), 0, 0, false);
                $alpha = clone $image->getCore();
                $image->getCore()->compositeImage($canvas, \Imagick::COMPOSITE_DEFAULT, 0, 0);
                $image->getCore()->compositeImage($tile, \Imagick::COMPOSITE_DEFAULT, 0, 0);
                $image->getCore()->compositeImage($alpha, \Imagick::COMPOSITE_COPYOPACITY, 0, 0);
            }
        } else {
            if($filling instanceof Image) {
                $image->setCore($image->getCore()->textureImage($filling->getCore()));
            } elseif($filling instanceof Color) {
                $draw = new \ImagickDraw();
                $draw->setFillColor($filling->getPixel());
                $draw->rectangle(0, 0, $image->getWidth(), $image->getHeight());
                $image->getCore()->drawImage($draw);
            }
        }
        return true;
    }
}