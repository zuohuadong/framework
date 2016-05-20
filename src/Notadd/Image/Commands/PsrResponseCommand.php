<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:21
 */
namespace Notadd\Image\Commands;
use GuzzleHttp\Psr7\Response;
/**
 * Class PsrResponseCommand
 * @package Notadd\Image\Commands
 */
class PsrResponseCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $format = $this->argument(0)->value();
        $quality = $this->argument(1)->between(0, 100)->value();
        $stream = $image->stream($format, $quality);
        $mimetype = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $image->getEncoded());
        $this->setOutput(new Response(200, array(
                'Content-Type' => $mimetype,
                'Content-Length' => strlen($image->getEncoded())
            ), $stream));
        return true;
    }
}