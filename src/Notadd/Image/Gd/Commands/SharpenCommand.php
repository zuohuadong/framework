<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:29
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class SharpenCommand
 * @package Notadd\Image\Gd\Commands
 */
class SharpenCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $amount = $this->argument(0)->between(0, 100)->value(10);
        $min = $amount >= 10 ? $amount * -0.01 : 0;
        $max = $amount * -0.025;
        $abs = ((4 * $min + 4 * $max) * -1) + 1;
        $div = 1;
        $matrix = array(
            array(
                $min,
                $max,
                $min
            ),
            array(
                $max,
                $abs,
                $max
            ),
            array(
                $min,
                $max,
                $min
            )
        );
        return imageconvolution($image->getCore(), $matrix, $div, 0);
    }
}