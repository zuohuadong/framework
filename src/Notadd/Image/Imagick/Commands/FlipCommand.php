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
 * Class FlipCommand
 * @package Notadd\Image\Imagick\Commands
 */
class FlipCommand extends AbstractCommand {
    /**
     * Mirrors an image
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $mode = $this->argument(0)->value('h');
        if(in_array(strtolower($mode), array(
            2,
            'v',
            'vert',
            'vertical'
        ))) {
            return $image->getCore()->flipImage();
        } else {
            return $image->getCore()->flopImage();
        }
    }
}