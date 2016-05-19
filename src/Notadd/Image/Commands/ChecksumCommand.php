<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:12
 */
namespace Notadd\Image\Commands;
/**
 * Class ChecksumCommand
 * @package Notadd\Image\Commands
 */
class ChecksumCommand extends AbstractCommand {
    /**
     * @param \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $colors = array();
        $size = $image->getSize();
        for($x = 0; $x <= ($size->width - 1); $x++) {
            for($y = 0; $y <= ($size->height - 1); $y++) {
                $colors[] = $image->pickColor($x, $y, 'array');
            }
        }
        $this->setOutput(md5(serialize($colors)));
        return true;
    }
}