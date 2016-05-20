<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:59
 */
namespace Notadd\Image\Imagick\Commands;
use Imagick;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class InterlaceCommand
 * @package Notadd\Image\Imagick\Commands
 */
class InterlaceCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $mode = $this->argument(0)->type('bool')->value(true);
        if($mode) {
            $mode = Imagick::INTERLACE_LINE;
        } else {
            $mode = Imagick::INTERLACE_NO;
        }
        $image->getCore()->setInterlaceScheme($mode);
        return true;
    }
}