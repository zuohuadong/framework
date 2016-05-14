<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:31
 */
namespace Notadd\Foundation\Image\Contracts;
/**
 * Interface Resolver
 * @package Notadd\Foundation\Image\Contracts
 */
interface Resolver {
    /**
     * @return mixed|bool
     */
    public function resolve();
    /**
     * @param mixed $id
     * @return mixed|bool
     * on success
     */
    public function resolveFromCache($id);
    /**
     * @return mixed|bool
     */
    public function getCached();
    /**
     * @param string $base
     * @return void
     */
    public function setResolveBase($base = '/');
    /**
     * @param string $parameter
     * @return void
     */
    public function setParameter($parameter);
    /**
     * @param string $source
     * @return void
     */
    public function setSource($source);
    /**
     * @param string $filter
     * @return void
     */
    public function setFilter($filter = null);
    /**
     * @access public
     * @return void
     */
    public function disableCache();
    /**
     * @param \Notadd\Foundation\Image\Contracts\Image $cachedImage
     * @return mixed
     */
    public function getCachedUrl(Image $cachedImage);
    /**
     * @param \Notadd\Foundation\Image\Contracts\Image $image
     * @return string
     */
    public function getImageUrl(Image $image);
    /**
     * @param  string $key
     * @return mixed|array
     */
    public function getParameter($key = null);
    /**
     * @access public
     * @return void
     */
    public function close();
}