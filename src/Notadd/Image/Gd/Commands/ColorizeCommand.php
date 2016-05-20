<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:57
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class ColorizeCommand
 * @package Notadd\Image\Gd\Commands
 */
class ColorizeCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $red = $this->argument(0)->between(-100, 100)->required()->value();
        $green = $this->argument(1)->between(-100, 100)->required()->value();
        $blue = $this->argument(2)->between(-100, 100)->required()->value();
        $red = round($red * 2.55);
        $green = round($green * 2.55);
        $blue = round($blue * 2.55);
        return imagefilter($image->getCore(), IMG_FILTER_COLORIZE, $red, $green, $blue);
    }
}