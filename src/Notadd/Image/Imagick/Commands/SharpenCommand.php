<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 19:09
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class SharpenCommand
 * @package Notadd\Image\Imagick\Commands
 */
class SharpenCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $amount = $this->argument(0)->between(0, 100)->value(10);
        return $image->getCore()->unsharpMaskImage(1, 1, $amount / 6.25, 0);
    }
}