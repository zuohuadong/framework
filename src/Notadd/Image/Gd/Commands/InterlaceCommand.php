<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:18
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class InterlaceCommand
 * @package Notadd\Image\Gd\Commands
 */
class InterlaceCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $mode = $this->argument(0)->type('bool')->value(true);
        imageinterlace($image->getCore(), $mode);
        return true;
    }
}