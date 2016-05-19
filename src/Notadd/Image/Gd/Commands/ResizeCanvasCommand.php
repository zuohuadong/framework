<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:26
 */
namespace Notadd\Image\Gd\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class ResizeCanvasCommand
 * @package Notadd\Image\Gd\Commands
 */
class ResizeCanvasCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $width = $this->argument(0)->type('digit')->required()->value();
        $height = $this->argument(1)->type('digit')->required()->value();
        $anchor = $this->argument(2)->value('center');
        $relative = $this->argument(3)->type('boolean')->value();
        $bgcolor = $this->argument(4)->value();
        $original_width = $image->getWidth();
        $original_height = $image->getHeight();
        $width = is_null($width) ? $original_width : intval($width);
        $height = is_null($height) ? $original_height : intval($height);
        if($relative) {
            $width = $original_width + $width;
            $height = $original_height + $height;
        }
        $width = ($width <= 0) ? $width + $original_width : $width;
        $height = ($height <= 0) ? $height + $original_height : $height;
        $canvas = $image->getDriver()->newImage($width, $height, $bgcolor);
        $canvas_size = $canvas->getSize()->align($anchor);
        $image_size = $image->getSize()->align($anchor);
        $canvas_pos = $image_size->relativePosition($canvas_size);
        $image_pos = $canvas_size->relativePosition($image_size);
        if($width <= $original_width) {
            $dst_x = 0;
            $src_x = $canvas_pos->x;
            $src_w = $canvas_size->width;
        } else {
            $dst_x = $image_pos->x;
            $src_x = 0;
            $src_w = $original_width;
        }
        if($height <= $original_height) {
            $dst_y = 0;
            $src_y = $canvas_pos->y;
            $src_h = $canvas_size->height;
        } else {
            $dst_y = $image_pos->y;
            $src_y = 0;
            $src_h = $original_height;
        }
        $transparent = imagecolorallocatealpha($canvas->getCore(), 255, 255, 255, 127);
        imagealphablending($canvas->getCore(), false);
        imagefilledrectangle($canvas->getCore(), $dst_x, $dst_y, $dst_x + $src_w - 1, $dst_y + $src_h - 1, $transparent);
        imagecopy($canvas->getCore(), $image->getCore(), $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
        $image->setCore($canvas->getCore());
        return true;
    }
}