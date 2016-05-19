<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:59
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Exceptions\InvalidArgumentException;
/**
 * Class CropCommand
 * @package Notadd\Image\Gd\Commands
 */
class CropCommand extends ResizeCommand {
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
        return $this->modify($image, 0, 0, $position->x, $position->y, $cropped->width, $cropped->height, $cropped->width, $cropped->height);
    }
}