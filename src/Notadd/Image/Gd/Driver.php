<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:31
 */
namespace Notadd\Image\Gd;
use Notadd\Image\AbstractColor;
use Notadd\Image\AbstractDriver;
use Notadd\Image\Exceptions\NotSupportedException;
use Notadd\Image\Image;
/**
 * Class Driver
 * @package Notadd\Image\Gd
 */
class Driver extends AbstractDriver {
    /**
     * @param Decoder $decoder
     * @param Encoder $encoder
     */
    public function __construct(Decoder $decoder = null, Encoder $encoder = null) {
        if(!$this->coreAvailable()) {
            throw new NotSupportedException("GD Library extension not available with this PHP installation.");
        }
        $this->decoder = $decoder ? $decoder : new Decoder;
        $this->encoder = $encoder ? $encoder : new Encoder;
    }
    /**
     * Creates new image instance
     * @param  integer $width
     * @param  integer $height
     * @param  string $background
     * @return \Notadd\Image\Image
     */
    public function newImage($width, $height, $background = null) {
        // create empty resource
        $core = imagecreatetruecolor($width, $height);
        $image = new Image(new static, $core);
        // set background color
        $background = new Color($background);
        imagefill($image->getCore(), 0, 0, $background->getInt());
        return $image;
    }
    /**
     * @param  string $value
     * @return AbstractColor
     */
    public function parseColor($value) {
        return new Color($value);
    }
    /**
     * Checks if core module installation is available
     * @return boolean
     */
    protected function coreAvailable() {
        return (extension_loaded('gd') && function_exists('gd_info'));
    }
    /**
     * Returns clone of given core
     * @return mixed
     */
    public function cloneCore($core) {
        $width = imagesx($core);
        $height = imagesy($core);
        $clone = imagecreatetruecolor($width, $height);
        imagealphablending($clone, false);
        imagesavealpha($clone, true);
        imagecopy($clone, $core, 0, 0, 0, 0, $width, $height);
        return $clone;
    }
}