<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:24
 */
namespace Notadd\Image\Commands;
/**
 * Class StreamCommand
 * @package Notadd\Image\Commands
 */
class StreamCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $format = $this->argument(0)->value();
        $quality = $this->argument(1)->between(0, 100)->value();
        $this->setOutput(\GuzzleHttp\Psr7\stream_for($image->encode($format, $quality)->getEncoded()));
        return true;
    }
}