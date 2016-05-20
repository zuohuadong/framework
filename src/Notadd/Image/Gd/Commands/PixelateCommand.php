<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:23
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class PixelateCommand
 * @package Notadd\Image\Gd\Commands
 */
class PixelateCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $size = $this->argument(0)->type('digit')->value(10);
        return imagefilter($image->getCore(), IMG_FILTER_PIXELATE, $size, true);
    }
}