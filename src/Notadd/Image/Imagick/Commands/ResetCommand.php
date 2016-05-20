<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 19:05
 */
namespace Notadd\Image\Imagick\Commands;
use Imagick;
use Notadd\Image\Commands\AbstractCommand;
use Notadd\Image\Exceptions\RuntimeException;
class ResetCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $backupName = $this->argument(0)->value();
        $backup = $image->getBackup($backupName);
        if($backup instanceof Imagick) {
            $image->getCore()->clear();
            $backup = clone $backup;
            $image->setCore($backup);
            return true;
        }
        throw new RuntimeException("Backup not available. Call backup({$backupName}) before reset().");
    }
}