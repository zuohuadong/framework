<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:23
 */
namespace Notadd\Image\Commands;
use Notadd\Image\Response;
/**
 * Class ResponseCommand
 * @package Notadd\Image\Commands
 */
class ResponseCommand extends AbstractCommand {
    /**
     * Builds HTTP response from given image
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $format = $this->argument(0)->value();
        $quality = $this->argument(1)->between(0, 100)->value();
        $response = new Response($image, $format, $quality);
        $this->setOutput($response->make());
        return true;
    }
}