<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:19
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class InvertCommand
 * @package Notadd\Image\Gd\Commands
 */
class InvertCommand extends AbstractCommand {
    /**
     * Inverts colors of an image
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        return imagefilter($image->getCore(), IMG_FILTER_NEGATE);
    }
}