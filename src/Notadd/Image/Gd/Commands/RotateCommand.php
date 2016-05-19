<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:29
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
use Notadd\Image\Gd\Color;
/**
 * Class RotateCommand
 * @package Notadd\Image\Gd\Commands
 */
class RotateCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $angle = $this->argument(0)->type('numeric')->required()->value();
        $color = $this->argument(1)->value();
        $color = new Color($color);
        $image->setCore(imagerotate($image->getCore(), $angle, $color->getInt()));
        return true;
    }
}