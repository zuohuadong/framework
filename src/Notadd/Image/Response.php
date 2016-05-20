<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 15:35
 */
namespace Notadd\Image;
/**
 * Class Response
 * @package Notadd\Image
 */
class Response {
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
     * @param Image $image
     * @param string $format
     * @param integer $quality
     */
    public function __construct(Image $image, $format = null, $quality = null) {
        $this->image = $image;
        $this->format = $format ? $format : $image->mime;
        $this->quality = $quality ? $quality : 90;
    }
    /**
     * @return mixed
     */
    public function make() {
        $this->image->encode($this->format, $this->quality);
        $data = $this->image->getEncoded();
        $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $data);
        $length = strlen($data);
        if(function_exists('app') && is_a($app = app(), 'Illuminate\Foundation\Application')) {
            $response = \Response::make($data);
            $response->header('Content-Type', $mime);
            $response->header('Content-Length', $length);
        } else {
            header('Content-Type: ' . $mime);
            header('Content-Length: ' . $length);
            $response = $data;
        }
        return $response;
    }
}