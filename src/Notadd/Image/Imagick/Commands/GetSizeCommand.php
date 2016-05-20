<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:56
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
use Notadd\Image\Size;
/**
 * Class GetSizeCommand
 * @package Notadd\Image\Imagick\Commands
 */
class GetSizeCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $core = $image->getCore();
        $this->setOutput(new Size($core->getImageWidth(), $core->getImageHeight()));
        return true;
    }
}