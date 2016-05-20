<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:03
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class DestroyCommand
 * @package Notadd\Image\Gd\Commands
 */
class DestroyCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        imagedestroy($image->getCore());
        foreach($image->getBackups() as $backup) {
            imagedestroy($backup);
        }
        return true;
    }
}