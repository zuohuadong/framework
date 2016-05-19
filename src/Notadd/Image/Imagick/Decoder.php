<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:35
 */
namespace Notadd\Image\Imagick;
use Notadd\Image\AbstractDecoder;
use Notadd\Image\Exceptions\NotReadableException;
use Notadd\Image\Exceptions\NotSupportedException;
use Notadd\Image\Image;
/**
 * Class Decoder
 * @package Notadd\Image\Imagick
 */
class Decoder extends AbstractDecoder {
    /**
     * @param  string $path
     * @return \Notadd\Image\Image
     */
    public function initFromPath($path) {
        $core = new \Imagick;
        try {
            $core->readImage($path);
            $core->setImageType(\Imagick::IMGTYPE_TRUECOLORMATTE);
        } catch(\ImagickException $e) {
            throw new NotReadableException("Unable to read image from path ({$path}).", 0, $e);
        }
        $image = $this->initFromImagick($core);
        $image->setFileInfoFromPath($path);
        return $image;
    }
    /**
     * @param Resource $resource
     * @return \Notadd\Image\Image|void
     */
    public function initFromGdResource($resource) {
        throw new NotSupportedException('Imagick driver is unable to init from GD resource.');
    }
    /**
     * @param \Imagick $object
     * @return \Notadd\Image\Image
     */
    public function initFromImagick(\Imagick $object) {
        $object = $this->removeAnimation($object);
        $object->setImageOrientation(\Imagick::ORIENTATION_UNDEFINED);
        return new Image(new Driver, $object);
    }
    /**
     * @param string $binary
     * @return \Notadd\Image\Image
     */
    public function initFromBinary($binary) {
        $core = new \Imagick;
        try {
            $core->readImageBlob($binary);
        } catch(\ImagickException $e) {
            throw new NotReadableException("Unable to read image from binary data.", 0, $e);
        }
        $image = $this->initFromImagick($core);
        $image->mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $binary);
        return $image;
    }
    /**
     * @param \Imagick $object
     * @return \Imagick
     */
    private function removeAnimation(\Imagick $object) {
        $imagick = new \Imagick;
        foreach($object as $frame) {
            $imagick->addImage($frame->getImage());
            break;
        }
        $object->destroy();
        return $imagick;
    }
}