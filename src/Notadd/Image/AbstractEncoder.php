<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 15:11
 */
namespace Notadd\Image;
use Notadd\Image\Exceptions\InvalidArgumentException;
use Notadd\Image\Exceptions\NotSupportedException;
/**
 * Class AbstractEncoder
 * @package Notadd\Image
 */
abstract class AbstractEncoder {
    /**
     * @var string
     */
    public $result;
    /**
     * @var Image
     */
    public $image;
    /**
     * @var string
     */
    public $format;
    /**
     * @var integer
     */
    public $quality;
    /**
     * @return string
     */
    abstract protected function processJpeg();
    /**
     * @return string
     */
    abstract protected function processPng();
    /**
     * @return string
     */
    abstract protected function processGif();
    /**
     * @return string
     */
    abstract protected function processTiff();
    /**
     * @return string
     */
    abstract protected function processBmp();
    /**
     * @return string
     */
    abstract protected function processIco();
    /**
     * @return string
     */
    abstract protected function processWebp();
    /**
     * @param Image $image
     * @param string $format
     * @param integer $quality
     * @return \Notadd\Image\Image
     * @throws \Notadd\Image\Exceptions\InvalidArgumentException
     * @throws \Notadd\Image\Exceptions\NotSupportedException
     */
    public function process(Image $image, $format = null, $quality = null) {
        $this->setImage($image);
        $this->setFormat($format);
        $this->setQuality($quality);
        switch(strtolower($this->format)) {
            case 'data-url':
                $this->result = $this->processDataUrl();
                break;
            case 'gif':
            case 'image/gif':
                $this->result = $this->processGif();
                break;
            case 'png':
            case 'image/png':
            case 'image/x-png':
                $this->result = $this->processPng();
                break;
            case 'jpg':
            case 'jpeg':
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->result = $this->processJpeg();
                break;
            case 'tif':
            case 'tiff':
            case 'image/tiff':
            case 'image/tif':
            case 'image/x-tif':
            case 'image/x-tiff':
                $this->result = $this->processTiff();
                break;
            case 'bmp':
            case 'image/bmp':
            case 'image/ms-bmp':
            case 'image/x-bitmap':
            case 'image/x-bmp':
            case 'image/x-ms-bmp':
            case 'image/x-win-bitmap':
            case 'image/x-windows-bmp':
            case 'image/x-xbitmap':
                $this->result = $this->processBmp();
                break;
            case 'ico':
            case 'image/x-ico':
            case 'image/x-icon':
            case 'image/vnd.microsoft.icon':
                $this->result = $this->processIco();
                break;
            case 'psd':
            case 'image/vnd.adobe.photoshop':
                $this->result = $this->processPsd();
                break;
            case 'webp':
                $this->result = $this->processWebp();
                break;
            default:
                throw new NotSupportedException("Encoding format ({$format}) is not supported.");
        }
        $this->setImage(null);
        return $image->setEncoded($this->result);
    }
    /**
     * @return string
     */
    protected function processDataUrl() {
        $mime = $this->image->mime ? $this->image->mime : 'image/png';
        return sprintf('data:%s;base64,%s', $mime, base64_encode($this->process($this->image, $mime, $this->quality)));
    }
    /**
     * @param Image $image
     */
    protected function setImage($image) {
        $this->image = $image;
    }
    /**
     * @param string $format
     * @return $this
     */
    protected function setFormat($format = null) {
        if($format == '' && $this->image instanceof Image) {
            $format = $this->image->mime;
        }
        $this->format = $format ? $format : 'jpg';
        return $this;
    }
    /**
     * @param integer $quality
     * @return $this
     * @throws \Notadd\Image\Exceptions\InvalidArgumentException
     */
    protected function setQuality($quality) {
        $quality = is_null($quality) ? 90 : $quality;
        $quality = $quality === 0 ? 1 : $quality;
        if($quality < 0 || $quality > 100) {
            throw new InvalidArgumentException('Quality must range from 0 to 100.');
        }
        $this->quality = intval($quality);
        return $this;
    }
}