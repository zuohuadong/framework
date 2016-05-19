<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:12
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class GammaCommand
 * @package Notadd\Image\Gd\Commands
 */
class GammaCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $gamma = $this->argument(0)->type('numeric')->required()->value();
        return imagegammacorrect($image->getCore(), 1, $gamma);
    }
}