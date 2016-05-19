<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:58
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class ContrastCommand
 * @package Notadd\Image\Gd\Commands
 */
class ContrastCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $level = $this->argument(0)->between(-100, 100)->required()->value();
        return imagefilter($image->getCore(), IMG_FILTER_CONTRAST, ($level * -1));
    }
}