<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:27
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class ResizeCommand
 * @package Notadd\Image\Gd\Commands
 */
class ResizeCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $width = $this->argument(0)->value();
        $height = $this->argument(1)->value();
        $constraints = $this->argument(2)->type('closure')->value();
        $resized = $image->getSize()->resize($width, $height, $constraints);
        $this->modify($image, 0, 0, 0, 0, $resized->getWidth(), $resized->getHeight(), $image->getWidth(), $image->getHeight());
        return true;
    }
    /**
     * @param  \Notadd\Image\Image $image
     * @param  integer $dst_x
     * @param  integer $dst_y
     * @param  integer $src_x
     * @param  integer $src_y
     * @param  integer $dst_w
     * @param  integer $dst_h
     * @param  integer $src_w
     * @param  integer $src_h
     * @return boolean
     */
    protected function modify($image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) {
        $modified = imagecreatetruecolor($dst_w, $dst_h);
        $resource = $image->getCore();
        $transIndex = imagecolortransparent($resource);
        if($transIndex != -1) {
            $rgba = imagecolorsforindex($modified, $transIndex);
            $transColor = imagecolorallocatealpha($modified, $rgba['red'], $rgba['green'], $rgba['blue'], 127);
            imagefill($modified, 0, 0, $transColor);
            imagecolortransparent($modified, $transColor);
        } else {
            imagealphablending($modified, false);
            imagesavealpha($modified, true);
        }
        $result = imagecopyresampled($modified, $resource, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
        $image->setCore($modified);
        return $result;
    }
}