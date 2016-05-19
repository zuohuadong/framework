<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:09
 */
namespace Notadd\Image\Commands;
/**
 * Class AbstractCommand
 * @package Notadd\Image\Commands
 */
abstract class AbstractCommand {
    /**
     * @var array
     */
    public $arguments;
    /**
     * @var mixed
     */
    protected $output;
    /**
     * @param  \Notadd\Image\Image $image
     * @return mixed
     */
    abstract public function execute($image);
    /**
     * @param array $arguments
     */
    public function __construct($arguments) {
        $this->arguments = $arguments;
    }
    /**
     * @param  integer $key
     * @return \Notadd\Image\Commands\Argument
     */
    public function argument($key) {
        return new Argument($this, $key);
    }
    /**
     * @return mixed
     */
    public function getOutput() {
        return $this->output ? $this->output : null;
    }
    /**
     * @return boolean
     */
    public function hasOutput() {
        return !is_null($this->output);
    }
    /**
     * @param mixed $value
     */
    public function setOutput($value) {
        $this->output = $value;
    }
}