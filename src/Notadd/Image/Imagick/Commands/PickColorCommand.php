<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 19:03
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
use Notadd\Image\Imagick\Color;
/**
 * Class PickColorCommand
 * @package Notadd\Image\Imagick\Commands
 */
class PickColorCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $x = $this->argument(0)->type('digit')->required()->value();
        $y = $this->argument(1)->type('digit')->required()->value();
        $format = $this->argument(2)->type('string')->value('array');
        $color = new Color($image->getCore()->getImagePixelColor($x, $y));
        $this->setOutput($color->format($format));
        return true;
    }
}