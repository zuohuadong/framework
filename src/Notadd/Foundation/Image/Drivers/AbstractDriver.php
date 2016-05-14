<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 12:07
 */
namespace Notadd\Foundation\Image\Drivers;
use Notadd\Foundation\Image\Contracts\Driver;
/**
 * Class AbstractDriver
 * @package Notadd\Foundation\Image\Drivers
 */
abstract class AbstractDriver implements Driver {
    /**
     * @var string
     */
    const FL_IGNR_ASPR = '!';
    /**
     * @var string
     */
    const FL_FILL_AREA = '^';
    /**
     * @var string
     */
    const FL_RESZ_PERC = '%';
    /**
     * @var string
     */
    const FL_PIXL_CLMT = '@';
    /**
     * @var string
     */
    const FL_OENL_SML = '<';
    /**
     * @var string
     */
    const FL_OSRK_LGR = '>';
    /**
     * @var int
     */
    const INT_FILTER = 0;
    /**
     * @var int
     */
    const EXT_FILTER = 1;
    /**
     * @var array
     */
    protected $filters = [];
    /**
     * @var array
     */
    protected $targetSize = [];
    /**
     * @var array
     */
    protected $sourceAttributes;
    /**
     * @var mixed
     */
    protected $outputType;
    /**
     * @var string
     */
    protected $error;
    /**
     * @var bool
     */
    protected $processed = false;
    /**
     * @return void
     */
    public function __destruct() {
        $this->clean();
    }
    /**
     * @param string $alias
     * @param string $class
     * @return void
     */
    public function registerFilter($alias, $class) {
        $this->filters[$alias] = $class;
    }
    /**
     * @return bool
     */
    public function isProcessed() {
        return $this->processed;
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
        $this->source = null;
        $this->resource = null;
        $this->processed = false;
        $this->targetSize = null;
        $this->outputType = null;
        $this->sourceAttributes = null;
    }
    /**
     * @return string
     */
    final public function getDriverType() {
        return static::$driverType;
    }
    /**
     * @param string $name
     * @param array $options
     * @return int
     */
    public function filter($name, array $options = []) {
        if(method_exists($this, $filter = 'filter' . ucfirst($name))) {
            call_user_func_array([
                $this,
                $filter
            ], is_array($options) ? $options : []);
            return static::INT_FILTER;
        }
        return static::EXT_FILTER;
    }
    /**
     * @param mixed $width
     * @param mixed $height
     * @return void
     */
    public function setTargetSize($width, $height) {
        $this->targetSize = compact('width', 'height');
    }
    /**
     * @return array
     */
    public function getTargetSize() {
        extract($this->targetSize);
        return $this->getImageSize($width, $height);
    }
    /**
     * @return mixed
     */
    public function getError() {
        return !is_null($this->error) ? $this->error : false;
    }
    /**
     * @param mixed $attribute
     * @return mixed
     */
    public function getInfo($attribute = null) {
        if(!isset($this->sourceAttributes)) {
            $this->sourceAttributes = $this->getSourceAttributes();
        }
        if(!is_null($attribute)) {
            return isset($this->sourceAttributes[$attribute]) ? $this->sourceAttributes[$attribute] : null;
        }
        return $this->sourceAttributes;
    }
    /**
     * @return mixed
     */
    public function getSource() {
        return $this->loader->getSource();
    }
    /**
     * @param mixed $type
     * @return void
     */
    public function setOutputType($type) {
        if(preg_match('/(png|gif|jpe?g|tif?f|webp)/i', $type)) {
            $this->outputType = sprintf('image/%s', strtolower($type));
            return;
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid output format %s', $type));
        }
    }
    /**
     * @param mixed $assSuffix
     * @return string
     */
    public function getSourceType($assSuffix = false) {
        $type = $this->getInfo('type');
        return (bool)$assSuffix ? strtr(preg_replace('~image/~', null, $this->formatType($type)), ['jpeg' => 'jpg']) : $type;
    }
    /**
     * @return string
     */
    public function getOutputType() {
        $type = $this->outputType;
        if(is_null($type)) {
            $type = $this->getInfo('type');
        }
        return preg_replace('~image/~', null, $this->formatType($type));
    }
    protected function formatType($type) {
        return strtolower(preg_replace('~jpg~', 'jpeg', $type));
    }
    /**
     * @return mixed
     */
    public function getOutputMimeType() {
        return image_type_to_mime_type($this->getImageTypeConstant($this->getOutputType()));
    }
    /**
     * @access protected
     */
    protected function filterResize() {
        $this->resize($this->targetSize['width'], $this->targetSize['height'], static::FL_IGNR_ASPR);
    }
    /**
     * Crop and resize filter.
     * @param int $gravity
     * @access protected
     */
    protected function filterCropScale($gravity) {
        $this->resize($this->targetSize['width'], $this->targetSize['height'], static::FL_FILL_AREA)->gravity($gravity)->extent($this->targetSize['width'], $this->targetSize['height']);
    }
    /**
     * @param int $gravity
     * @param null $background
     */
    protected function filterCrop($gravity, $background = null) {
        $this->background($background)->gravity($gravity)->extent($this->targetSize['width'], $this->targetSize['height']);
    }
    /**
     * @access protected
     * @return void
     */
    protected function filterResizeToFit() {
        $this->resize($this->targetSize['width'], $this->targetSize['height'], static::FL_OSRK_LGR);
    }
    /**
     * @access protected
     * @return void
     */
    protected function filterPercentualScale() {
        $this->resize($this->targetSize['width'], 0, static::FL_RESZ_PERC);
    }
    /**
     * @access protected
     * @return void
     */
    protected function filterResizePixelCount() {
        $this->resize($this->targetSize['width'], 0, static::FL_PIXL_CLMT);
    }
    /**
     * @param int $width
     * @param int $height
     * @param string $flag
     * @abstract
     * @return void
     */
    abstract protected function resize($width, $height, $flag = '');
    /**
     * @param int $gravity
     * @param string $flag
     * @abstract
     * @return void
     */
    abstract protected function gravity($gravity, $flag = '');
    /**
     * @param int $width
     * @param int $height
     * @param string $flag
     * @abstract
     * @return void
     */
    abstract protected function extent($width, $height, $flag = '');
    /**
     * @param string $color
     * @abstract
     * @return void
     */
    abstract protected function background($color = null);
    /**
     * @param mixed $width
     * @param mixed $height
     * @return array
     */
    protected function getImageSize($width, $height) {
        $min = min($width, $height);
        if(0 === $min) {
            if(0 === max($width, $height)) {
                extract($this->getInfo());
            } else {
                $ratio = $this->getInfo('ratio');
            }
            $width = $width === 0 ? (int)floor($height * $ratio) : $width;
            $height = $height === 0 ? (int)floor($width / $ratio) : $height;
        }
        return compact('width', 'height');
    }
    /**
     * @access protected
     * @return array
     */
    protected function getSourceAttributes() {
        $info = getimagesize($this->source);
        list($width, $height) = $info;
        return [
            'width' => $width,
            'height' => $height,
            'ratio' => $this->ratio($width, $height),
            'size' => filesize($this->source),
            'type' => $info['mime']
        ];
    }
    /**
     * @param mixed $type
     * @return int
     */
    private function getImageTypeConstant($type) {
        switch($type) {
            case 'jpg':
            case 'jpeg':
                return IMAGETYPE_JPEG;
            case 'gif':
                return IMAGETYPE_GIF;
            case 'png':
                return IMAGETYPE_PNG;
            case 'webp':
                return IMAGETYPE_WBMP;
            case 'webp':
                return IMAGETYPE_WBMP;
            case 'ico':
                return IMAGETYPE_ICO;
            case 'bmp':
                return IMAGETYPE_BMP;
            default:
                return IMAGETYPE_JPC;
        }
    }
}