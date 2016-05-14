<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 12:21
 */
namespace Notadd\Foundation\Image\Drivers;
use Notadd\Foundation\Image\Contracts\SourceLoader;
use Notadd\Foundation\Image\Traits\Scaling;
/**
 * Class GdDriver
 * @package Notadd\Foundation\Image\Drivers
 */
class GdDriver extends AbstractDriver {
    use Scaling;
    /**
     * @var mixed
     */
    protected $resource;
    /**
     * @var mixed
     */
    protected $source;
    /**
     * @var mixed
     */
    protected $gravity;
    /**
     * @var string
     */
    protected $outputType;
    /**
     * @var int
     */
    protected $quality = 80;
    /**
     * @var bool|integer
     */
    private $background;
    /**
     * GdDriver constructor.
     * @param \Notadd\Foundation\Image\Contracts\SourceLoader $loader
     */
    public function __construct(SourceLoader $loader) {
        $this->loader = $loader;
    }
    /**
     * @param string $source
     * @return bool
     */
    public function load($source) {
        $this->clean();
        if(!$src = $this->loadResourceFromType($source)) {
            return false;
        }
        $this->resource = $src;
        return true;
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
     * @param mixed $quality
     * @return mixed
     */
    public function setQuality($quality) {
        $this->quality = $quality;
    }
    /**
     * @return string
     */
    public function getImageBlob() {
        $fn = sprintf('image%s', $type = $this->getOutputType());
        if('png' === $type) {
            imagesavealpha($this->resource, true);
        }
        ob_start();
        call_user_func($fn, $this->resource, null, $this->getQuality());
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    /**
     * @return mixed
     */
    private function getQuality() {
        if('png' === $this->getOutputType()) {
            return floor((9 / 100) * min(100, $this->quality));
        }
        return $this->quality;
    }
    /**
     * @param mixed $name
     * @param mixed $options
     * @return mixed
     */
    public function filter($name, array $options = []) {
        if(static::EXT_FILTER === parent::filter($name, $options) and isset($this->filters[$name])) {
            $filter = new $this->filters[$name]($this, $options);
            $filter->run();
        }
    }
    /**
     * @param mixed $source
     * @return mixed
     */
    private function loadResourceFromType($source) {
        $mime = null;
        extract(getimagesize($source));
        $fn = sprintf('imagecreatefrom%s', $type = substr($mime, strpos($mime, '/') + 1));
        if(!function_exists($fn)) {
            $this->error = sprintf('%s is not a supported image type', $mime);
            $this->clean();
            throw new \InvalidArgumentException(sprintf('Unsupported image format %s', $mime));
        }
        $this->source = $this->loader->load($source);
        $this->outputType = $mime;
        return call_user_func($fn, $this->source);
    }
    /**
     * @return void
     */
    public function clean() {
        if(is_resource($this->resource)) {
            imagedestroy($this->resource);
        }
        parent::clean();
    }
    /**
     * @return mixed
     */
    public function getResource() {
        return $this->resource;
    }
    /**
     * @param mixed $resource
     * @return void
     */
    public function swapResource($resource) {
        if(!is_resource($resource) or 'gd' !== get_resource_type($resource)) {
            throw new \InvalidArgumentException('No resource given or wrong resource type');
        }
        $this->resource = $resource;
    }
    /**
     * @param mixed $width
     * @param mixed $height
     * @param mixed $flag
     * @return mixed
     */
    protected function resize($width, $height, $flag = null) {
        if(0 === min($width, $height)) {
            extract($this->getTargetSize());
        }
        switch($flag) {
            case static::FL_IGNR_ASPR:
                break;
            case static::FL_FILL_AREA:
                $this->fillArea($width, $height, $this->getInfo('width'), $this->getInfo('height'));
                break;
            case static::FL_OSRK_LGR:
                extract($this->fitInBounds($width, $height, $this->getInfo('width'), $this->getInfo('height')));
                break;
            case static::FL_RESZ_PERC:
                extract($this->percentualScale($this->getInfo('width'), $this->getInfo('height'), $width));
                break;
            case static::FL_PIXL_CLMT:
                extract($this->pixelLimit($this->getInfo('width'), $this->getInfo('height'), $width));
                break;
            default:
                $r1 = $this->getInfo('ratio');
                $r2 = $this->ratio($width, $height);
                if(0.001 < abs($r1 - $r2)) {
                    extract($this->getImageSize($width, 0));
                }
                break;
        }
        $resized = imagecreatetruecolor($width, $height);
        imagecopyresampled($resized, $this->resource, 0, 0, 0, 0, $width, $height, $this->getInfo('width'), $this->getInfo('height'));
        $this->swapResource($resized);
        return $this;
    }
    /**
     * @param mixed $gravity
     * @return mixed
     */
    protected function gravity($gravity, $flag = '') {
        $this->gravity = $gravity;
        return $this;
    }
    /**
     * @param mixed $color
     * @return mixed
     */
    protected function background($color = null) {
        if(!is_null($color)) {
            $this->background = $this->getColorID($color);
            extract($this->getBackgroundCoordinates($this->getGravity()));
            imagefill($this->resource, $x1, $y1, $this->background);
            imagefill($this->resource, $x2, $x2, $this->background);
        }
        return $this;
    }
    /**
     * @return void
     */
    public function process() {
        parent::process();
        if(is_int($this->background)) {
            extract($this->getBackgroundCoordinates($this->getGravity()));
            imagefill($this->resource, $x1, $y1, $this->background);
            imagefill($this->resource, $x2, $y2, $this->background);
        }
    }
    /**
     * @param mixed $color
     * @return int|boolean
     */
    private function getColorID($color) {
        list ($r, $g, $b) = explode(' ', implode(' ', str_split(strtoupper(3 === strlen($color) ? $color . $color : $color), 2)));
        return imagecolorallocate($this->resource, hexdec($r), hexdec($g), hexdec($b));
    }
    /**
     * @param mixed $width
     * @param mixed $height
     * @param mixed $flag
     * @return mixed
     */
    protected function extent($width, $height, $flag = null) {
        $w = $this->getInfo('width');
        $h = $this->getInfo('height');
        extract($this->getCropCoordinates($nw = imagesx($this->resource), $nh = imagesy($this->resource), $width, $height, $this->getGravity()));
        $extent = imagecreatetruecolor($width, $height);
        imagecopy($extent, $this->resource, 0, 0, $x, $y, $width, $height);
        $this->swapResource($extent);
        return $this;
    }
    /**
     * @return int
     */
    protected function getGravity() {
        return is_null($this->gravity) ? 1 : $this->gravity;
    }
    /**
     * @param mixed $gravity
     * @return array
     */
    private function getBackgroundCoordinates($gravity) {
        $w = imagesx($this->resource);
        $h = imagesy($this->resource);
        $x1 = $y1 = 0;
        $x2 = $w - 1;
        $y2 = $h - 1;
        switch($gravity) {
            case 1:
                $x1 = $w - 1;
                $y1 = $h - 1;
                $y2 = 0;
                break;
            case 5:
                $x1 = $w - 1;
                $y2 = $w - 1;
                $x2 = 0;
                $y2 = 0;
                break;
            case 9:
                break;
            default:
                $x2 = 0;
                $y2 = 0;
                break;
        }
        return compact('x1', 'y1', 'x2', 'y2');
    }
}