<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:25
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
use Notadd\Image\Exceptions\RuntimeException;
/**
 * Class ResetCommand
 * @package Notadd\Image\Gd\Commands
 */
class ResetCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $backupName = $this->argument(0)->value();
        if(is_resource($backup = $image->getBackup($backupName))) {
            imagedestroy($image->getCore());
            $backup = $image->getDriver()->cloneCore($backup);
            $image->setCore($backup);
            return true;
        }
        throw new RuntimeException("Backup not available. Call backup() before reset().");
    }
}