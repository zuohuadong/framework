<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:48
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class BackupCommand
 * @package Notadd\Image\Imagick\Commands
 */
class BackupCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $backupName = $this->argument(0)->value();
        $image->setBackup(clone $image->getCore(), $backupName);
        return true;
    }
}