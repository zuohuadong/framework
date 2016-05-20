<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:25
 */
namespace Notadd\Image\Commands;
use Closure;
/**
 * Class TextCommand
 * @package Notadd\Image\Commands
 */
class TextCommand extends AbstractCommand {
    /**
     * Write text on given image
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $text = $this->argument(0)->required()->value();
        $x = $this->argument(1)->type('numeric')->value(0);
        $y = $this->argument(2)->type('numeric')->value(0);
        $callback = $this->argument(3)->type('closure')->value();
        $fontclassname = sprintf('\Notadd\Image\%s\Font', $image->getDriver()->getDriverName());
        $font = new $fontclassname($text);
        if($callback instanceof Closure) {
            $callback($font);
        }
        $font->applyToImage($image, $x, $y);
        return true;
    }
}