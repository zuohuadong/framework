<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:13
 */
namespace Notadd\Image\Commands;
use Closure;
/**
 * Class CircleCommand
 * @package Notadd\Image\Commands
 */
class CircleCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\image $image
     * @return boolean
     */
    public function execute($image) {
        $diameter = $this->argument(0)->type('numeric')->required()->value();
        $x = $this->argument(1)->type('numeric')->required()->value();
        $y = $this->argument(2)->type('numeric')->required()->value();
        $callback = $this->argument(3)->type('closure')->value();
        $circle_classname = sprintf('\Notadd\Image\%s\Shapes\CircleShape', $image->getDriver()->getDriverName());
        $circle = new $circle_classname($diameter);
        if($callback instanceof Closure) {
            $callback($circle);
        }
        $circle->applyToImage($image, $x, $y);
        return true;
    }
}