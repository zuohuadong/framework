<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:37
 */
namespace Notadd\Image\Imagick;
use Imagick;
use Notadd\Image\AbstractDriver;
use Notadd\Image\Exceptions\NotSupportedException;
use Notadd\Image\Image;
/**
 * Class Driver
 * @package Notadd\Image\Imagick
 */
class Driver extends AbstractDriver {
    /**
     * @param \Notadd\Image\Imagick\Decoder|null $decoder
     * @param \Notadd\Image\Imagick\Encoder|null $encoder
     */
    public function __construct(Decoder $decoder = null, Encoder $encoder = null) {
        if(!$this->coreAvailable()) {
            throw new NotSupportedException("ImageMagick module not available with this PHP installation.");
        }
        $this->decoder = $decoder ? $decoder : new Decoder;
        $this->encoder = $encoder ? $encoder : new Encoder;
    }
    /**
     * @param  integer $width
     * @param  integer $height
     * @param  string $background
     * @return Image
     */
    public function newImage($width, $height, $background = null) {
        $background = new Color($background);
        $core = new Imagick;
        $core->newImage($width, $height, $background->getPixel(), 'png');
        $core->setType(Imagick::IMGTYPE_UNDEFINED);
        $core->setImageType(Imagick::IMGTYPE_UNDEFINED);
        $core->setColorspace(Imagick::COLORSPACE_UNDEFINED);
        $image = new Image(new static, $core);
        return $image;
    }
    /**
     * @param  string $value
     * @return \Notadd\Image\AbstractColor
     */
    public function parseColor($value) {
        return new Color($value);
    }
    /**
     * @return boolean
     */
    protected function coreAvailable() {
        return (extension_loaded('imagick') && class_exists('Imagick'));
    }
}