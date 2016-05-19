<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 15:17
 */
namespace Notadd\Image;
/**
 * Class File
 * @package Notadd\Image
 */
class File {
    /**
     * @var string
     */
    public $mime;
    /**
     * @var string
     */
    public $dirname;
    /**
     * @var string
     */
    public $basename;
    /**
     * @var string
     */
    public $extension;
    /**
     * @var string
     */
    public $filename;
    /**
     * @param string $path
     * @return $this
     */
    public function setFileInfoFromPath($path) {
        $info = pathinfo($path);
        $this->dirname = array_key_exists('dirname', $info) ? $info['dirname'] : null;
        $this->basename = array_key_exists('basename', $info) ? $info['basename'] : null;
        $this->extension = array_key_exists('extension', $info) ? $info['extension'] : null;
        $this->filename = array_key_exists('filename', $info) ? $info['filename'] : null;
        if(file_exists($path) && is_file($path)) {
            $this->mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
        }
        return $this;
    }
    /**
     * @return mixed
     */
    public function filesize() {
        $path = $this->basePath();
        if(file_exists($path) && is_file($path)) {
            return filesize($path);
        }
        return false;
    }
    /**
     * @return string
     */
    public function basePath() {
        if($this->dirname && $this->basename) {
            return ($this->dirname . '/' . $this->basename);
        }
        return null;
    }
}