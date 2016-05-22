<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 15:24
 */
namespace Notadd\Image;
use Closure;
use Illuminate\Container\Container;
use Notadd\Image\Exceptions\MissingDependencyException;
use Notadd\Image\Exceptions\NotSupportedException;
/**
 * Class ImageManager
 * @package Notadd\Image
 */
class ImageManager {
    /**
     * @var array
     */
    public $config = array(
        'driver' => 'gd'
    );
    /**
     * ImageManager constructor.
     * @param array $config
     */
    public function __construct(array $config = array()) {
        $this->checkRequirements();
        $this->configure($config);
    }
    /**
     * @param array $config
     * @return $this
     */
    public function configure(array $config = array()) {
        $this->config = array_replace($this->config, $config);
        $this->config['driver'] = Container::getInstance()->make('setting')->get('attachment.engine', 'gd');
        return $this;
    }
    /**
     * @param  mixed $data
     * @return \Notadd\Image\Image
     */
    public function make($data) {
        return $this->createDriver()->init($data);
    }
    /**
     * @param  integer $width
     * @param  integer $height
     * @param  mixed $background
     * @return \Notadd\Image\Image
     */
    public function canvas($width, $height, $background = null) {
        return $this->createDriver()->newImage($width, $height, $background);
    }
    /**
     * @param \Closure $callback
     * @param integer $lifetime
     * @param boolean $returnObj
     * @return \Notadd\Image\Image
     * @throws MissingDependencyException
     */
    public function cache(Closure $callback, $lifetime = null, $returnObj = false) {
        if(class_exists('Notadd\\Image\\ImageCache')) {
            $imagecache = new ImageCache($this);
            if(is_callable($callback)) {
                $callback($imagecache);
            }
            return $imagecache->get($lifetime, $returnObj);
        }
        throw new MissingDependencyException("Please install package imagecache before running this function.");
    }
    /**
     * @return \Notadd\Image\AbstractDriver
     * @throws NotSupportedException
     */
    private function createDriver() {
        $drivername = ucfirst($this->config['driver']);
        $driverclass = sprintf('Notadd\\Image\\%s\\Driver', $drivername);
        if(class_exists($driverclass)) {
            return new $driverclass;
        }
        throw new NotSupportedException("Driver ({$drivername}) could not be instantiated.");
    }
    /**
     * @throws \Notadd\Image\Exceptions\MissingDependencyException
     */
    private function checkRequirements() {
        if(!function_exists('finfo_buffer')) {
            throw new MissingDependencyException("PHP Fileinfo extension must be installed/enabled to use Notadd Image.");
        }
    }
}