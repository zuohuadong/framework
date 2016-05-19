<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:15
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
use Notadd\Image\Size;
/**
 * Class GetSizeCommand
 * @package Notadd\Image\Gd\Commands
 */
class GetSizeCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $this->setOutput(new Size(imagesx($image->getCore()), imagesy($image->getCore())));
        return true;
    }
}