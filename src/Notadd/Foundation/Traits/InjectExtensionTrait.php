<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-18 00:24
 */
namespace Notadd\Foundation\Traits;
use Illuminate\Container\Container;
/**
 * Class InjectExtensionTrait
 * @package Notadd\Foundation\Traits
 */
trait InjectExtensionTrait {
    /**
     * @var \Notadd\Extension\ExtensionManager
     */
    private $extension;
    /**
     * @return \Notadd\Extension\ExtensionManager
     */
    public function getExtension() {
        if(!isset($this->extension)) {
            $this->extension = Container::getInstance()->make('extension');
        }
        return $this->extension;
    }
}