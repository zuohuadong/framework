<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:32
 */
namespace Notadd\Image\Gd\Commands;
/**
 * Class WidenCommand
 * @package Notadd\Image\Gd\Commands
 */
class WidenCommand extends ResizeCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $width = $this->argument(0)->type('digit')->required()->value();
        $additionalConstraints = $this->argument(1)->type('closure')->value();
        $this->arguments[0] = $width;
        $this->arguments[1] = null;
        $this->arguments[2] = function ($constraint) use ($additionalConstraints) {
            $constraint->aspectRatio();
            if(is_callable($additionalConstraints))
                $additionalConstraints($constraint);
        };
        return parent::execute($image);
    }
}