<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:32
 */
namespace Notadd\Foundation\Image\Filters;
use Exception;
use Notadd\Foundation\Image\Contracts\Driver;
use Notadd\Foundation\Image\Contracts\Filter;
use Notadd\Foundation\Image\Traits\ProcessingTrait;
/**
 * Class AbstractFilter
 * @package Notadd\Foundation\Image\Filters
 */
abstract class AbstractFilter implements Filter {
    use ProcessingTrait;
    /**
     * @var mixed
     */
    protected $driver;
    /**
     * @var array
     */
    protected $options;
    /**
     * @var array
     */
    protected $availableOptions = [];
    /**
     * @return void
     */
    abstract public function run();
    /**
     * AbstractFilter constructor.
     * @param \Notadd\Foundation\Image\Contracts\Driver $driver
     * @param $options
     */
    final public function __construct(Driver $driver, $options) {
        $this->driver = $driver;
        $this->setOptions($options);
        $this->ensureCompat();
    }
    /**
     * @param array $options
     * @return void
     */
    protected function setOptions(array $options) {
        $this->options = [];
        foreach($options as $option => $value) {
            if(!in_array($option, (array)$this->availableOptions)) {
                throw new \InvalidArgumentException(sprintf('filter %s has no option "%s"', get_class($this), $option));
            }
            $this->options[$option] = $value;
        }
    }
    /**
     * @param string $option
     * @param mixed $default 
     * @return mixed
     */
    public function getOption($option, $default = null) {
        if(array_key_exists($option, $this->options)) {
            return $this->options[$option];
        }
        return $default;
    }
    /**
     * @throws Exception
     * @return void
     */
    private function ensureCompat() {
        if(!static::$driverType) {
            throw new Exception(sprintf('trying to apply incopatible filter on %s driver', $this->driver->getDriverType()));
        }
    }
}