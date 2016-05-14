<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 12:58
 */
namespace Notadd\Foundation\Image\Caches;
use Notadd\Foundation\Image\Contracts\Image as ImageContract;
use Notadd\Foundation\Image\Contracts\Resolver;
/**
 * Class CachedImage
 * @package Notadd\Foundation\Image\Caches
 */
class CachedImage implements ImageContract {
    private $resource;
    private $source;
    private $finfo;
    private $closed;
    /**
     * CachedImage constructor.
     * @param null $src
     */
    public function __construct($src = null) {
        $src && $this->load($src);
    }
    /**
     * @return void
     */
    public function __destruct() {
        $this->close();
    }
    /**
     * @param string $source
     * @return bool|void
     */
    public function load($source) {
        $this->close();
        $this->source = $source;
        $this->resource = finfo_open(FILEINFO_MIME_TYPE);
        $this->finfo = [
            'mime' => finfo_file($this->resource, $this->source),
            'lastmod' => filemtime($this->source)
        ];
        $this->closed = false;
    }
    /**
     * @return mixed
     */
    public function getSource() {
        return $this->source;
    }
    /**
     * @return mixed
     */
    protected function isClosed() {
        return $this->closed;
    }
    /**
     * @return int
     */
    public function getLastModTime() {
        if($this->isClosed()) {
            return time();
        }
        return $this->finfo['lastmod'];
    }
    /**
     *
     */
    public function close() {
        if($this->isClosed()) {
            return;
        }
        if(is_resource($this->resource)) {
            finfo_close($this->resource);
        };
        $this->source = null;
        $this->resource = null;
        $this->finfo = null;
        $this->closed = true;
    }
    /**
     * @return mixed
     */
    public function getMimeType() {
        if(!$this->isClosed()) {
            return $this->finfo['mime'];
        }
    }
    /**
     * @return bool
     */
    public function isProcessed() {
        return false;
    }
    /**
     * @param \Notadd\Foundation\Image\Contracts\Resolver $resolver
     */
    public function process(Resolver $resolver) {
        throw new \LogicException(sprintf('calling process() on a cached image is not allowed, called with %s', get_class($resolver)));
    }
    /**
     * @param $quality
     */
    public function setQuality($quality) {
        throw new \LogicException(sprintf('calling setQuality() on a cached image is not allowed, called with %s', get_class($quality)));
    }
    /**
     * @param $format
     */
    public function setFileFormat($format) {
        throw new \LogicException(sprintf('calling setFileFormat() on a cached image is not allowed, called with %s', get_class($format)));
    }
    /**
     * @return string
     */
    public function getContents() {
        return file_get_contents($this->source);
    }
    /**
     * @return mixed
     */
    public function getFileFormat() {
        if($this->isClosed()) {
            return;
        }
        if(!isset($this->finfo['extension'])) {
            $this->finfo['extension'] = preg_replace([
                '~image/~',
                '~.*jpeg~i'
            ], [
                '',
                'jpg'
            ], $this->getMimeType());
        }
        return $this->finfo['extension'];
    }
    /**
     * @return mixed
     */
    public function getSourceFormat() {
        return $this->getFileFormat();
    }
    /**
     * @return mixed
     */
    public function getSourceMimeTime() {
        return $this->getMimeType();
    }
}