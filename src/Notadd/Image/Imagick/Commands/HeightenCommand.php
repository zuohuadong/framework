<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:58
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Gd\Commands\ResizeCommand;
/**
 * Class HeightenCommand
 * @package Notadd\Image\Imagick\Commands
 */
class HeightenCommand extends ResizeCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $height = $this->argument(0)->type('digit')->required()->value();
        $additionalConstraints = $this->argument(1)->type('closure')->value();
        $this->arguments[0] = null;
        $this->arguments[1] = $height;
        $this->arguments[2] = function ($constraint) use ($additionalConstraints) {
            $constraint->aspectRatio();
            if(is_callable($additionalConstraints))
                $additionalConstraints($constraint);
        };
        return parent::execute($image);
    }
}