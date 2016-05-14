<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 15:10
 */
namespace Notadd\Foundation\Image;
use Notadd\Foundation\Image\Contracts\Driver;
use Notadd\Foundation\Image\Contracts\Image as  ImageContract;
use Notadd\Foundation\Image\Contracts\Resolver;
/**
 * Class Image
 * @package Notadd\Foundation\Image
 */
class Image implements ImageContract {
    /**
     * @var int
     */
    const IM_NOSCALE = 0;
    /**
     * @var int
     */
    const IM_RESIZE = 1;
    /**
     * @var int
     */
    const IM_SCALECROP = 2;
    /**
     * @var int
     */
    const IM_CROP = 3;
    /**
     * @var int
     */
    const IM_RSIZEFIT = 4;
    /**
     * @var int
     */
    const IM_RSIZEPERCENT = 5;
    /**
     * @var int
     */
    const IM_RSIZEPXCOUNT = 6;
    /**
     * @var \Notadd\Foundation\Image\Contracts\Driver
     */
    protected $driver;
    /**
     * compression
     * @var int
     */
    protected $compression = 80;
    /**
     * attributes
     * @var array
     */
    protected $attributes = [];
    /**
     * Image constructor.
     * @param \Notadd\Foundation\Image\Contracts\Driver $driver
     * @param null $source
     */
    public function __construct(Driver $driver, $source = null) {
        $this->driver = $driver;
        if(!is_null($source)) {
            $this->load($source);
        }
    }
    /**
     * @param string $source
     * @return bool|void
     */
    public function load($source) {
        return $this->driver->load($source);
    }
    /**
     * @param \Notadd\Foundation\Image\Contracts\Resolver $resolver
     */
    public function process(Resolver $resolver) {
        $params = $resolver->getParameter();
        $this->driver->setTargetSize($params['width'], $params['height']);
        switch($params['mode']) {
            case static::IM_NOSCALE:
                break;
            case static::IM_RESIZE:
                $this->resize();
                break;
            case static::IM_SCALECROP:
                $this->cropScale($params['gravity']);
                break;
            case static::IM_CROP:
                $this->crop($params['gravity'], $params['background']);
                break;
            case static::IM_RSIZEFIT:
                $this->resizeToFit();
                break;
            case static::IM_RSIZEPERCENT:
                $this->resizePercentual($params['width']);
                break;
            case static::IM_RSIZEPXCOUNT:
                $this->resizePixelCount($params['width']);
                break;
            default:
                break;
        }
        foreach($params['filter'] as $f => $parameter) {
            $this->addFilter($f, $parameter);
        }
        $this->driver->setQuality($this->compression);
        $this->driver->process();
    }
    /**
     * @param int $quality
     */
    public function setQuality($quality) {
        $this->compression = $quality;
    }
    /**
     * @param string $format
     */
    public function setFileFormat($format) {
        return $this->driver->setOutputType($format);
    }
    /**
     * @return string
     */
    public function getContents() {
        return $this->driver->getImageBlob();
    }
    /**
     * @return mixed
     */
    public function getFileFormat() {
        return $this->driver->getOutputType();
    }
    /**
     * @return string
     */
    public function getSourceFormat() {
        return $this->driver->getSourceType(true);
    }
    /**
     * @return string
     */
    public function getSourceMimeTime() {
        return $this->driver->getSourceType(false);
    }
    /**
     * @return mixed
     */
    public function getMimeType() {
        return $this->driver->getOutputMimeType();
    }
    /**
     * @return mixed
     */
    public function getSource() {
        return $this->driver->getSource();
    }
    /**
     * 
     */
    public function close() {
        return $this->driver->clean();
    }
    /**
     * @return bool
     */
    public function isProcessed() {
        return $this->driver->isProcessed();
    }
    /**
     * @return int
     */
    public function getLastModTime() {
        if($this->isProcessed()) {
            return time();
        }
        return filemtime($this->driver->getSource());
    }
    /**
     * @param $filter
     * @param array $options
     */
    protected function addFilter($filter, array $options = []) {
        $this->driver->filter($filter, $options);
    }
    /**
     * @return bool|void
     */
    protected function resize() {
        return $this->driver->filter('resize', func_get_args());
    }
    /**
     * @return bool|void
     */
    protected function cropScale() {
        return $this->driver->filter('cropScale', func_get_args());
    }
    /**
     * @return bool|void
     */
    protected function crop() {
        return $this->driver->filter('crop', func_get_args());
    }
    /**
     * @return bool|void
     */
    protected function resizeToFit() {
        return $this->driver->filter('resizeToFit', func_get_args());
    }
    /**
     * @return bool|void
     */
    protected function resizePercentual() {
        return $this->driver->filter('percentualScale', func_get_args());
    }
    /**
     * @return bool|void
     */
    protected function resizePixelCount() {
        return $this->driver->filter('resizePixelCount', func_get_args());
    }
}