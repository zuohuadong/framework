<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:55
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class BackupCommand
 * @package Notadd\Image\Gd\Commands
 */
class BackupCommand extends AbstractCommand {
    /**
     * Saves a backups of current state of image core
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $backupName = $this->argument(0)->value();
        $size = $image->getSize();
        $clone = imagecreatetruecolor($size->width, $size->height);
        imagealphablending($clone, false);
        imagesavealpha($clone, true);
        $transparency = imagecolorallocatealpha($clone, 0, 0, 0, 127);
        imagefill($clone, 0, 0, $transparency);
        imagecopy($clone, $image->getCore(), 0, 0, 0, 0, $size->width, $size->height);
        $image->setBackup($clone, $backupName);
        return true;
    }
}