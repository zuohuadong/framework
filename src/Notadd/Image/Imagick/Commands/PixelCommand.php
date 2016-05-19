<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 19:05
 */
namespace Notadd\Image\Imagick\Commands;
use ImagickDraw;
use Notadd\Image\Commands\AbstractCommand;
use Notadd\Image\Imagick\Color;
/**
 * Class PixelCommand
 * @package Notadd\Image\Imagick\Commands
 */
class PixelCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $color = $this->argument(0)->required()->value();
        $color = new Color($color);
        $x = $this->argument(1)->type('digit')->required()->value();
        $y = $this->argument(2)->type('digit')->required()->value();
        $draw = new ImagickDraw;
        $draw->setFillColor($color->getPixel());
        $draw->point($x, $y);
        return $image->getCore()->drawImage($draw);
    }
}