<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 15:27
 */
namespace Notadd\Foundation\Image;
use Notadd\Foundation\Image\Contracts\Resolver;
/**
 * Class ImageAdapter
 * @package Notadd\Foundation\Image
 */
class ImageAdapter {
    /**
     * @var mixed
     */
    protected $resolver;
    /**
     * @var mixed
     */
    protected $targetSize;
    /**
     * @var mixed
     */
    protected $mode;
    /**
     * @var string
     */
    protected $base;
    /**
     * @var array
     */
    protected $filters;
    /**
     * ImageAdapter constructor.
     * @param \Notadd\Foundation\Image\Contracts\Resolver $resolver
     * @param string $base
     */
    public function __construct(Resolver $resolver, $base = '/') {
        $this->resolver = $resolver;
        $this->filters = [];
        $this->base = $base;
    }
    /**
     * @param $source
     * @return $this
     */
    public function source($source) {
        $this->clean();
        $this->source = $source;
        return $this;
    }
    /**
     * @return \Notadd\Foundation\Image\ImageAdapter
     */
    public function toJpeg() {
        return $this->filter('conv', ['f' => 'jpg']);
    }
    /**
     * @return \Notadd\Foundation\Image\ImageAdapter
     */
    public function toPng() {
        return $this->filter('conv', ['f' => 'png']);
    }
    /**
     * @return \Notadd\Foundation\Image\ImageAdapter
     */
    public function toGif() {
        return $this->filter('conv', ['f' => 'gif']);
    }
    /**
     * @param $width
     * @param $height
     * @return string
     */
    public function resize($width, $height) {
        $this->mode = 'resize';
        $this->targetSize = [
            $width,
            $height
        ];
        $this->arguments = [];
        return $this->process();
    }
    /**
     * @param $width
     * @param $height
     * @param $gravity
     * @param null $background
     * @return string
     */
    public function crop($width, $height, $gravity, $background = null) {
        $this->mode = 'crop';
        $this->targetSize = [
            $width,
            $height
        ];
        $this->arguments = [
            $gravity,
            $background
        ];
        return $this->process();
    }
    /**
     * @param $width
     * @param $height
     * @param $gravity
     * @return string
     */
    public function cropAndResize($width, $height, $gravity) {
        $this->mode = 'cropResize';
        $this->targetSize = [
            $width,
            $height
        ];
        $this->arguments = [$gravity];
        return $this->process();
    }
    /**
     * @param $width
     * @param $height
     * @return string
     */
    public function fit($width, $height) {
        $this->mode = 'resizeToFit';
        $this->targetSize = [
            $width,
            $height
        ];
        $this->arguments = [];
        return $this->process();
    }
    /**
     * @param $percent
     * @return string
     */
    public function scale($percent) {
        $this->mode = 'percentualScale';
        $this->targetSize = [
            $percent,
            null
        ];
        $this->arguments = [];
        return $this->process();
    }
    /**
     * @param $pixel
     * @return string
     */
    public function pixel($pixel) {
        $this->mode = 'resizePixelCount';
        $this->targetSize = [
            $pixel,
            null
        ];
        $this->arguments = [];
        return $this->process();
    }
    /**
     * @return string
     */
    public function get() {
        if($this->targetSize) {
            throw new \InvalidArgumentException('can\'t get original iamge if target size is already set');
        }
        $this->mode = 'default';
        $this->arguments = [];
        return $this->process();
    }
    /**
     * @param mixed $name
     * @param mixed $options
     * @access public
     * @return $this
     */
    public function filter($name, $options = null) {
        $this->filters[$name] = $options;
        return $this;
    }
    /**
     * @param array $filters
     * @access public
     * @return $this
     */
    public function filters(array $filters) {
        foreach($filters as $name => $options) {
            $this->filter($name, $options);
        }
        return $this;
    }
    /**
     * @return string
     */
    protected function process() {
        extract($this->compileExpression());
        $this->clean();
        $this->resolver->close();
        $this->resolver->setParameter($params);
        $this->resolver->setSource($source);
        $this->resolver->setFilter($filter);
        if($image = $this->resolver->getCached()) {
            $src = $this->base . $this->resolver->getCachedUrl($image);
            $extension = $image->getSourceFormat(true);
            $this->resolver->close();
            $image->close();
            return $src . '.' . $extension;
        }
        if($image = $this->resolver->resolve($image)) {
            $src = $this->resolver->getImageUrl($image);
            $this->resolver->close();
            $image->close();
            return $src;
        }
        $this->resolver->close();
        return;
    }
    /**
     * @return array
     */
    protected function compileExpression() {
        $parts = [$this->getMode()];
        foreach($this->targetSize as $value) {
            if(is_numeric($value)) {
                $parts[] = (string)$value;
            }
        }
        foreach($this->arguments as $i => $arg) {
            if(is_numeric($arg) || ($i === 1 and $this->isColor($arg))) {
                $parts[] = trim((string)$arg);
            }
        }
        $source = $this->source;
        $params = implode('/', $parts);
        $filter = $this->compileFilterExpression();
        return compact('source', 'params', 'filter');
    }
    /**
     * @return string|null
     */
    private function compileFilterExpression() {
        $filters = [];
        foreach($this->filters as $filter => $options) {
            $opt = [];
            if(is_array($options)) {
                foreach($options as $option => $value) {
                    $opt[] = sprintf('%s=%s', $option, $value);
                }
            }
            $filters[] = sprintf('%s;%s', $filter, implode(';', $opt));
        }
        if(!empty($filters)) {
            return sprintf('filter:%s', implode(':', $filters));
        }
        return null;
    }
    /**
     * @return void
     */
    private function clean() {
        $this->mode = null;
        $this->source = null;
        $this->filters = [];
        $this->targetSize = [];
    }
    /**
     * @param mixed $color
     * @return boolean
     */
    private function isColor($color) {
        return preg_match('#^[0-9a-fA-F]{3}|^[0-9a-fA-F]{6}#', $color);
    }
    /**
     * @return int
     */
    protected function getMode() {
        switch($this->mode) {
            case 'resize':
                return 1;
            case 'cropResize':
                return 2;
            case 'crop':
                return 3;
            case 'resizeToFit':
                return 4;
            case 'percentualScale':
                return 5;
            case 'resizePixelCount':
                return 6;
            default:
                return 0;
        }
    }
}