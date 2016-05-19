<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:05
 */
namespace Notadd\Image\Gd\Commands;
/**
 * Class FlipCommand
 * @package Notadd\Image\Gd\Commands
 */
class FlipCommand extends ResizeCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $mode = $this->argument(0)->value('h');
        $size = $image->getSize();
        $dst = clone $size;
        switch(strtolower($mode)) {
            case 2:
            case 'v':
            case 'vert':
            case 'vertical':
                $size->pivot->y = $size->height - 1;
                $size->height = $size->height * (-1);
                break;
            default:
                $size->pivot->x = $size->width - 1;
                $size->width = $size->width * (-1);
                break;
        }
        return $this->modify($image, 0, 0, $size->pivot->x, $size->pivot->y, $dst->width, $dst->height, $size->width, $size->height);
    }
}