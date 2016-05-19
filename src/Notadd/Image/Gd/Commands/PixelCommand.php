<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:24
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
use Notadd\Image\Gd\Color;
/**
 * Class PixelCommand
 * @package Notadd\Image\Gd\Commands
 */
class PixelCommand extends AbstractCommand {
    /**
     * Draws one pixel to a given image
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $color = $this->argument(0)->required()->value();
        $color = new Color($color);
        $x = $this->argument(1)->type('digit')->required()->value();
        $y = $this->argument(2)->type('digit')->required()->value();
        return imagesetpixel($image->getCore(), $x, $y, $color->getInt());
    }
}