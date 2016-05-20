<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:52
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class DestroyCommand
 * @package Notadd\Image\Imagick\Commands
 */
class DestroyCommand extends AbstractCommand {
    /**
     * Destroys current image core and frees up memory
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $image->getCore()->clear();
        foreach($image->getBackups() as $backup) {
            $backup->clear();
        }
        return true;
    }
}