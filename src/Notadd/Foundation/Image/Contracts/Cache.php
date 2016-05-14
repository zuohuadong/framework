<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:43
 */
namespace Notadd\Foundation\Image\Contracts;
/**
 * Interface Cache
 * @package Notadd\Foundation\Image\Contracts
 */
interface Cache {
    /**
     * @param string $key
     * @param bool $raw
     * @return mixed|string|void
     */
    public function get($key, $raw = false);
    /**
     * @param string $key
     * @return boolean
     */
    public function has($key);
    /**
     * @param string $key
     * @param string $contents
     * @return void
     */
    public function put($key, $contents);
    /**
     * @param string $key
     * @return void
     */
    public function delete($key);
    /**
     * @return void
     */
    public function purge();
    /**
     * @param string $path
     * @return string
     */
    public function getRelPath($path);
    /**
     * @param string $url
     * @return string
     */
    public function getIdFromUrl($url);
    /**
     * @param string $src
     * @param string $fingerprint
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function createKey($src, $fingerprint = null, $prefix = 'io', $suffix = 'f');
}