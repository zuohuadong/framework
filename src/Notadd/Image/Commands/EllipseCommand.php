<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:14
 */
namespace Notadd\Image\Commands;
use Closure;
/**
 * Class EllipseCommand
 * @package Notadd\Image\Commands
 */
class EllipseCommand extends AbstractCommand {
    /**
     * Draws ellipse on given image
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $width = $this->argument(0)->type('numeric')->required()->value();
        $height = $this->argument(1)->type('numeric')->required()->value();
        $x = $this->argument(2)->type('numeric')->required()->value();
        $y = $this->argument(3)->type('numeric')->required()->value();
        $callback = $this->argument(4)->type('closure')->value();
        $ellipse_classname = sprintf('\Notadd\Image\%s\Shapes\EllipseShape', $image->getDriver()->getDriverName());
        $ellipse = new $ellipse_classname($width, $height);
        if($callback instanceof Closure) {
            $callback($ellipse);
        }
        $ellipse->applyToImage($image, $x, $y);
        return true;
    }
}