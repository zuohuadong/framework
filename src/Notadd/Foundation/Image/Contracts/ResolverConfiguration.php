<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:36
 */
namespace Notadd\Foundation\Image\Contracts;
/**
 * Interface ResolverConfiguration
 * @package Notadd\Foundation\Image\Contracts
 */
interface ResolverConfiguration {
    /**
     * @param $attribute
     * @param $value
     * @return bool|void false on failure
     */
    public function set($attribute, $value);
    /**
     * @param array $attributes
     * @return void
     */
    public function setAttributesArray(array $attributes);
    /**
     * @param mixed $attribute
     * @return mixed
     */
    public function get($attribute = null);
    /**
     * @param string $attribute
     * @return mixed
     */
    public function __get($attribute);
}