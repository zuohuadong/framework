<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:32
 */
namespace Notadd\Image\Gd;
use Notadd\Image\AbstractEncoder;
use Notadd\Image\Exceptions\NotSupportedException;
/**
 * Class Encoder
 * @package Notadd\Image\Gd
 */
class Encoder extends AbstractEncoder {
    /**
     * Processes and returns encoded image as JPEG string
     * @return string
     */
    protected function processJpeg() {
        ob_start();
        imagejpeg($this->image->getCore(), null, $this->quality);
        $this->image->mime = image_type_to_mime_type(IMAGETYPE_JPEG);
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
    /**
     * @return string
     */
    protected function processPng() {
        ob_start();
        $resource = $this->image->getCore();
        imagealphablending($resource, false);
        imagesavealpha($resource, true);
        imagepng($resource, null, -1);
        $this->image->mime = image_type_to_mime_type(IMAGETYPE_PNG);
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
    /**
     * @return string
     */
    protected function processGif() {
        ob_start();
        imagegif($this->image->getCore());
        $this->image->mime = image_type_to_mime_type(IMAGETYPE_GIF);
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
    /**
     * @return string
     */
    protected function processTiff() {
        throw new NotSupportedException("TIFF format is not supported by Gd Driver.");
    }
    /**
     * @return string
     */
    protected function processBmp() {
        throw new NotSupportedException("BMP format is not supported by Gd Driver.");
    }
    /**
     * @return string
     */
    protected function processIco() {
        throw new NotSupportedException("ICO format is not supported by Gd Driver.");
    }
    /**
     * @return string
     */
    protected function processPsd() {
        throw new NotSupportedException("PSD format is not supported by Gd Driver.");
    }
}