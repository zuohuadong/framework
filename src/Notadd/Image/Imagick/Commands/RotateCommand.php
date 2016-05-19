<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 19:08
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
use Notadd\Image\Imagick\Color;
/**
 * Class RotateCommand
 * @package Notadd\Image\Imagick\Commands
 */
class RotateCommand extends AbstractCommand {
    /**
     * Rotates image counter clockwise
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $angle = $this->argument(0)->type('numeric')->required()->value();
        $color = $this->argument(1)->value();
        $color = new Color($color);
        $image->getCore()->rotateImage($color->getPixel(), ($angle * -1));
        return true;
    }
}