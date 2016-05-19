<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:49
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class BrightnessCommand
 * @package Notadd\Image\Imagick\Commands
 */
class BrightnessCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $level = $this->argument(0)->between(-100, 100)->required()->value();
        return $image->getCore()->modulateImage(100 + $level, 100, 100);
    }
}