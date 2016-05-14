<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 12:47
 */
namespace Notadd\Foundation\Image\Drivers;
use Imagick;
use Notadd\Foundation\Image\Contracts\SourceLoader;
use Notadd\Foundation\Image\Traits\Scaling;
/**
 * Class ImagickDriver
 * @package Notadd\Foundation\Image\Drivers
 */
class ImagickDriver extends ImDriver {
    use Scaling;
    /**
     * @var string
     */
    protected static $driverType = 'imagick';
    /**
     * @var mixed
     */
    protected $resource;
    /**
     * ImagickDriver constructor.
     * @param \Notadd\Foundation\Image\Contracts\SourceLoader $loader
     */
    public function __construct(SourceLoader $loader) {
        $this->tmp = sys_get_temp_dir();
        $this->loader = $loader;
    }
    /**
     * @return mixed
     */
    public function __destruct() {
        parent::__destruct();
        if(is_resource($this->resource)) {
            $this->resource->destroy();
        }
    }
    /**
     * @return mixed
     */
    public function &getResource() {
        return $this->resource;
    }
    /**
     * @param mixed $resource
     * @return mixed
     */
    public function swapResource($resource) {
        if(false === ($resource instanceof Imagick)) {
            throw new \InvalidArgumentException('Wrong resource type');
        }
        $this->resource = $resource;
    }
    /**
     * @param mixed $type
     * @return mixed
     */
    public function setOutputType($type) {
        if(preg_match('/(png|gif|jpe?g|tif?f|webp)/i', $type)) {
            $this->resource->setImageFormat($type);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid output format %s', $type));
        }
    }
    /**
     * @return mixed
     */
    public function getOutputType() {
        return $this->formatType($this->resource->getImageFormat());
    }
    /**
     * @param mixed $source
     * @return mixed
     */
    public function load($source) {
        $this->clean();
        if($src = $this->loader->load($source)) {
            $this->source = $src;
            $this->resource = new Imagick($source);
            $this->getInfo();
            return true;
        }
        $this->error = 'error loading source';
        return false;
    }
    /**
     * @param mixed $name
     * @param mixed $options
     * @return void
     */
    public function filter($name, array $options = []) {
        $result = static::INT_FILTER;
        if($this->isMultipartImage()) {
            $this->resource = $this->resource->coalesceImages();
        }
        foreach($this->resource as $frame) {
            $result = $this->callParentFilter($name, $options);
        }
        if(static::EXT_FILTER === $result and isset($this->filters[$name])) {
            $filter = new $this->filters[$name]($this, $options);
            foreach($this->resource as $frame) {
                $filter->run();
            }
        }
    }
    /**
     * @return string
     */
    public function getImageBlob() {
        if(!$this->processed) {
            return file_get_contents($this->source);
        }
        if($this->isMultipartImage()) {
            $this->tmpFile = tempnam($this->tmp, 'jitim_');
            $image = $this->resource->deconstructImages();
            $image->writeImages($this->tmpFile, true);
            $image->clear();
            $image->destroy();
            return file_get_contents($this->tmpFile);
        }
        return $this->resource->getImageBlob();
    }
    /**
     * @param mixed $color
     * @return mixed
     */
    public function setBackgroundColor($color) {
        $this->resource->setImageBackgroundColor($color);
    }
    /**
     * @return mixed
     */
    public function setQuality($quality) {
        $this->resource->setImageCompressionQuality($quality);
    }
    /**
     * @return void
     */
    public function process() {
        $this->processed = true;
    }
    /**
     * @return void
     */
    public function clean() {
        if($this->resource instanceof Imagick) {
            $this->resource->destroy();
        }
        parent::clean();
    }
    /**
     * @access protected
     * @return void
     */
    protected function filterResizeToFit() {
        $this->resize($this->targetSize['width'], $this->targetSize['height'], static::FL_OSRK_LGR);
    }
    /**
     * @param mixed $gravity
     * @param string $flag
     * @access protected
     * @return $this
     */
    protected function gravity($gravity, $flag = '') {
        $this->resource->setGravity($gravity);
        return $this;
    }
    /**
     * @access protected
     * @return mixed
     */
    protected function repage() {
        $this->resource->setImagePage(0, 0, 0, 0);
    }
    /**
     * @param mixed $color
     * @access protected
     * @return $this
     */
    protected function background($color = null) {
        if(!is_null($color)) {
            $this->resource->setImageBackgroundColor(sprintf('#%s', $color));
        }
        return $this;
    }
    /**
     * @param mixed $width
     * @param mixed $height
     * @param string $flag
     * @return $this
     */
    protected function extent($width, $height, $flag = '') {
        extract($this->getCropCoordinates($this->resource->getImageWidth(), $this->resource->getImageHeight(), $width, $height, $this->resource->getGravity()));
        $this->resource->extentImage($width, $height, $x, $y);
        return $this;
    }
    /**
     * @param mixed $width
     * @param mixed $height
     * @param string $flag
     * @access protected
     * @return $this
     */
    protected function resize($width, $height, $flag = '') {
        switch($flag) {
            case static::FL_FILL_AREA:
                $this->fillArea($width, $height, $this->getInfo('width'), $this->getInfo('height'));
                break;
            case static::FL_IGNR_ASPR:
                break;
            case static::FL_PIXL_CLMT:
                extract($this->pixelLimit($this->getInfo('width'), $this->getInfo('height'), $width));
                break;
            case static::FL_RESZ_PERC:
                extract($this->percentualScale($this->getInfo('width'), $this->getInfo('height'), $width));
                break;
            case static::FL_OSRK_LGR:
                extract($this->fitInBounds($width, $height, $this->getInfo('width'), $this->getInfo('height')));
                break;
            default:
                $height = 0;
                break;
        }
        if($width > $this->getInfo('width') or $height > $this->getInfo('height')) {
            $filter = Imagick::FILTER_CUBIC;
            $blur = 0.6;
        } else {
            $filter = Imagick::FILTER_SINC;
            $blur = 1;
        }
        $this->resource->resizeImage($width, $height, $filter, $blur);
        return $this;
    }
    /**
     * @return array
     */
    protected function getSourceAttributes() {
        extract($this->resource->getImageGeometry());
        return [
            'width' => $width,
            'height' => $height,
            'ratio' => $this->ratio($width, $height),
            'size' => $this->resource->getImageLength(),
            'type' => sprintf('image/%s', strtolower($this->resource->getImageFormat())),
        ];
    }
    /**
     * @return mixed
     */
    protected function isMultipartImage() {
        return $this->resource->getNumberImages() > 1;
    }
    /**
     * @return mixed
     */
    private function callParentFilter() {
        return call_user_func_array([
            $this,
            'Thapp\JitImage\Driver\AbstractDriver::filter'
        ], func_get_args());
    }
}