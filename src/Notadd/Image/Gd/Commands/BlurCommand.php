<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:56
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class BlurCommand
 * @package Notadd\Image\Gd\Commands
 */
class BlurCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $amount = $this->argument(0)->between(0, 100)->value(1);
        for($i = 0; $i < intval($amount); $i++) {
            imagefilter($image->getCore(), IMG_FILTER_GAUSSIAN_BLUR);
        }
        return true;
    }
}