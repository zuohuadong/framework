<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:22
 */
namespace Notadd\Image\Commands;
use Closure;
/**
 * Class RectangleCommand
 * @package Notadd\Image\Commands
 */
class RectangleCommand extends AbstractCommand {
    /**
     * Draws rectangle on given image
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $x1 = $this->argument(0)->type('numeric')->required()->value();
        $y1 = $this->argument(1)->type('numeric')->required()->value();
        $x2 = $this->argument(2)->type('numeric')->required()->value();
        $y2 = $this->argument(3)->type('numeric')->required()->value();
        $callback = $this->argument(4)->type('closure')->value();
        $rectangle_classname = sprintf('\Notadd\Image\%s\Shapes\RectangleShape', $image->getDriver()->getDriverName());
        $rectangle = new $rectangle_classname($x1, $y1, $x2, $y2);
        if($callback instanceof Closure) {
            $callback($rectangle);
        }
        $rectangle->applyToImage($image, $x1, $y1);
        return true;
    }
}