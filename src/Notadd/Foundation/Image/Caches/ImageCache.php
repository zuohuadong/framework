<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 13:01
 */
namespace Notadd\Foundation\Image\Caches;
use Exception;
use FilesystemIterator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\NamespacedItemResolver;
use Notadd\Foundation\Image\Contracts\Cache;
use Notadd\Foundation\Image\Contracts\Image;
/**
 * Class ImageCache
 * @package Notadd\Foundation\Image\Caches
 */
class ImageCache extends NamespacedItemResolver implements Cache {
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * @var array
     */
    protected $pool = [];
    /**
     * @var string
     */
    protected $path;
    /**
     * @var \Notadd\Foundation\Image\Contracts\Image
     */
    protected $image;
    /**
     * ImageCache constructor.
     * @param \Notadd\Foundation\Image\Contracts\Image $image
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param $path
     * @param int $permission
     */
    public function __construct(Image $image, Filesystem $files, $path, $permission = 0777) {
        $this->image = $image;
        $this->files = $files;
        $this->setPath($path, $permission);
    }
    /**
     * @param string $key
     * @param bool $raw
     * @return \Notadd\Foundation\Image\Contracts\Image
     */
    public function get($key, $raw = false) {
        if($this->has($key)) {
            $this->image->close();
            $this->image->load($this->pool[$key]);
            return $raw ? $this->image->getImageBlob() : $this->image;
        }
    }
    /**
     * @param $key
     * @return bool
     */
    public function has($key) {
        if(array_key_exists($key, $this->pool)) {
            return true;
        }
        if($this->files->exists($path = $this->getPath($key))) {
            $this->pool[$key] = $path;
            return true;
        }
        return false;
    }
    /**
     * @param $path
     * @return string
     */
    public function getRelPath($path) {
        return ltrim(substr($path, strlen($this->path)), '\\\/');
    }
    /**
     * @param $url
     * @return string
     */
    public function getIdFromUrl($url) {
        $parts = preg_split('~/~', $url, -1, PREG_SPLIT_NO_EMPTY);
        return implode('.', array_slice($parts, count($parts) >= 2 ? -2 : -1));
    }
    /**
     * @param $src
     * @param null $fingerprint
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function createKey($src, $fingerprint = null, $prefix = 'io', $suffix = 'file') {
        return sprintf('%s.%s%s%s', substr(hash('sha1', $src), 0, 8), $prefix, $this->pad($src, $fingerprint), $this->pad($src, $suffix, 3));
    }
    /**
     * @param $key
     * @param $contents
     */
    public function put($key, $contents) {
        if(false === $this->has($key)) {
            $this->files->put($this->realizeDir($key), $contents);
        }
    }
    /**
     * @return void
     */
    public function purge() {
        try {
            foreach(new FilesystemIterator($this->path, FilesystemIterator::SKIP_DOTS) as $file) {
                $this->files->delete($file);
            }
        } catch(Exception $e) {
        }
    }
    /**
     * @param $key
     */
    public function delete($key) {
        $id = $this->createKey($key);
        $dir = substr($id, 0, strpos($id, '.'));
        if($this->files->exists($dir = $this->path . '/' . $dir)) {
            $this->files->deleteDirectory($dir, true);
        }
    }
    /**
     * @param $id
     * @return string
     */
    protected function getFilePath($id) {
        return sprintf('%s/%s', $this->path, $id);
    }
    /**
     * @param  string $key the cache key
     * @return string cache file path
     */
    protected function realizeDir($key) {
        $path = $this->getPath($key);
        if(!$this->files->exists($dir = dirname($path))) {
            $this->files->makeDirectory($dir);
        }
        return $path;
    }
    /**
     * @param string $key
     * @return string
     */
    protected function getPath($key) {
        $parsed = $this->parseKey($key);
        array_shift($parsed);
        list ($dir, $file) = $parsed;
        return sprintf('%s/%s/%s', $this->path, $dir, $file);
    }
    /**
     * @param string $src
     * @param string $pad
     * @param int $len
     * @return string
     */
    protected function pad($src, $pad, $len = 16) {
        return substr(hash('sha1', sprintf('%s%s', $src, $pad)), 0, $len);
    }
    /**
     * @param string $path path to cache directory
     * @param int $permission octal permission level
     * @return void
     */
    protected function setPath($path, $permission) {
        if(true !== $this->files->exists($path)) {
            $this->files->makeDirectory($path, $permission, true);
        }
        $this->path = $path;
    }
}