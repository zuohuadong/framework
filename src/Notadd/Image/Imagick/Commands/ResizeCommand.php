<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 19:07
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class ResizeCommand
 * @package Notadd\Image\Imagick\Commands
 */
class ResizeCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $width = $this->argument(0)->value();
        $height = $this->argument(1)->value();
        $constraints = $this->argument(2)->type('closure')->value();
        $resized = $image->getSize()->resize($width, $height, $constraints);
        $image->getCore()->scaleImage($resized->getWidth(), $resized->getHeight());
        return true;
    }
}