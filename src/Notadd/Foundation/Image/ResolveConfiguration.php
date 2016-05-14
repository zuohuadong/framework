<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 15:18
 */
namespace Notadd\Foundation\Image;
use Notadd\Foundation\Image\Contracts\ResolverConfiguration;
/**
 * Class ResolveConfiguration
 * @package Notadd\Foundation\Image
 */
class ResolveConfiguration implements ResolverConfiguration {
    /**
     * @var array
     */
    protected $attributes = [];
    /**
     * @var array
     */
    protected static $allowedAttributes = [
        'cache',
        'base',
        'trusted_sites',
        'cache_prefix',
        'cache_route',
        'base_route',
        'format_filter'
    ];
    /**
     * ResolveConfiguration constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        return $this->setAttributesArray($data);
    }
    /**
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function set($attribute, $value) {
        if(!in_array($attribute, static::$allowedAttributes)) {
            return false;
        }
        $this->attributes[$attribute] = $value;
    }
    /**
     * @param array $attributes
     */
    public function setAttributesArray(array $attributes) {
        foreach($attributes as $attribute => $value) {
            $this->set($attribute, $value);
        }
    }
    /**
     * @param null $attribute
     * @return array|mixed|null|void
     */
    public function get($attribute = null) {
        if(is_null($attribute)) {
            return $this->attributes;
        }
        if(!in_array($attribute, static::$allowedAttributes)) {
            return;
        }
        return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : null;
    }
    /**
     * @param string $attribute
     * @return array|mixed|null|void
     */
    public function __get($attribute) {
        return $this->get($attribute);
    }
}