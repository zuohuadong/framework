<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:51
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
use Notadd\Image\Exceptions\InvalidArgumentException;
use Notadd\Image\Point;
use Notadd\Image\Size;
/**
 * Class CropCommand
 * @package Notadd\Image\Imagick\Commands
 */
class CropCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $width = $this->argument(0)->type('digit')->required()->value();
        $height = $this->argument(1)->type('digit')->required()->value();
        $x = $this->argument(2)->type('digit')->value();
        $y = $this->argument(3)->type('digit')->value();
        if(is_null($width) || is_null($height)) {
            throw new InvalidArgumentException("Width and height of cutout needs to be defined.");
        }
        $cropped = new Size($width, $height);
        $position = new Point($x, $y);
        if(is_null($x) && is_null($y)) {
            $position = $image->getSize()->align('center')->relativePosition($cropped->align('center'));
        }
        $image->getCore()->cropImage($cropped->width, $cropped->height, $position->x, $position->y);
        $image->getCore()->setImagePage(0, 0, 0, 0);
        return true;
    }
}