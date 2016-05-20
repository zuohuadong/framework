<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:22
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class OpacityCommand
 * @package Notadd\Image\Gd\Commands
 */
class OpacityCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $transparency = $this->argument(0)->between(0, 100)->required()->value();
        $size = $image->getSize();
        $mask_color = sprintf('rgba(0, 0, 0, %.1f)', $transparency / 100);
        $mask = $image->getDriver()->newImage($size->width, $size->height, $mask_color);
        $image->mask($mask->getCore(), true);
        return true;
    }
}