<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:19
 */
namespace Notadd\Image\Commands;
/**
 * Class OrientateCommand
 * @package Notadd\Image\Commands
 */
class OrientateCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        switch($image->exif('Orientation')) {
            case 2:
                $image->flip();
                break;
            case 3:
                $image->rotate(180);
                break;
            case 4:
                $image->rotate(180)->flip();
                break;
            case 5:
                $image->rotate(270)->flip();
                break;
            case 6:
                $image->rotate(270);
                break;
            case 7:
                $image->rotate(90)->flip();
                break;
            case 8:
                $image->rotate(90);
                break;
        }
        return true;
    }
}