<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 19:03
 */
namespace Notadd\Image\Imagick\Commands;
use Imagick;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class OpacityCommand
 * @package Notadd\Image\Imagick\Commands
 */
class OpacityCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $transparency = $this->argument(0)->between(0, 100)->required()->value();
        $transparency = $transparency > 0 ? (100 / $transparency) : 1000;
        return $image->getCore()->evaluateImage(Imagick::EVALUATE_DIVIDE, $transparency, Imagick::CHANNEL_ALPHA);
    }
}