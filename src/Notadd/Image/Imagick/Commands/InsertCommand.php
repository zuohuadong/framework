<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:58
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class InsertCommand
 * @package Notadd\Image\Imagick\Commands
 */
class InsertCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $source = $this->argument(0)->required()->value();
        $position = $this->argument(1)->type('string')->value();
        $x = $this->argument(2)->type('digit')->value(0);
        $y = $this->argument(3)->type('digit')->value(0);
        $watermark = $image->getDriver()->init($source);
        $image_size = $image->getSize()->align($position, $x, $y);
        $watermark_size = $watermark->getSize()->align($position);
        $target = $image_size->relativePosition($watermark_size);
        return $image->getCore()->compositeImage($watermark->getCore(), \Imagick::COMPOSITE_DEFAULT, $target->x, $target->y);
    }
}