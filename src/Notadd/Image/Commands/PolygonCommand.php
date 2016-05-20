<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:20
 */
namespace Notadd\Image\Commands;
use Closure;
use Notadd\Image\Exceptions\InvalidArgumentException;
/**
 * Class PolygonCommand
 * @package Notadd\Image\Commands
 */
class PolygonCommand extends AbstractCommand {
    /**
     * @param \Notadd\Image\image $image
     * @return boolean
     */
    public function execute($image) {
        $points = $this->argument(0)->type('array')->required()->value();
        $callback = $this->argument(1)->type('closure')->value();
        $vertices_count = count($points);
        // check if number if coordinates is even
        if($vertices_count % 2 !== 0) {
            throw new InvalidArgumentException("The number of given polygon vertices must be even.");
        }
        if($vertices_count < 6) {
            throw new InvalidArgumentException("You must have at least 3 points in your array.");
        }
        $polygon_classname = sprintf('\Notadd\Image\%s\Shapes\PolygonShape', $image->getDriver()->getDriverName());
        $polygon = new $polygon_classname($points);
        if($callback instanceof Closure) {
            $callback($polygon);
        }
        $polygon->applyToImage($image);
        return true;
    }
}