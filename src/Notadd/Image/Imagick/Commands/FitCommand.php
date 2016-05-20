<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:55
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class FitCommand
 * @package Notadd\Image\Imagick\Commands
 */
class FitCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $width = $this->argument(0)->type('digit')->required()->value();
        $height = $this->argument(1)->type('digit')->value($width);
        $constraints = $this->argument(2)->type('closure')->value();
        $position = $this->argument(3)->type('string')->value('center');
        $cropped = $image->getSize()->fit(new Size($width, $height), $position);
        $resized = clone $cropped;
        $resized = $resized->resize($width, $height, $constraints);
        $image->getCore()->cropImage($cropped->width, $cropped->height, $cropped->pivot->x, $cropped->pivot->y);
        $image->getCore()->scaleImage($resized->getWidth(), $resized->getHeight());
        $image->getCore()->setImagePage(0, 0, 0, 0);
        return true;
    }
}