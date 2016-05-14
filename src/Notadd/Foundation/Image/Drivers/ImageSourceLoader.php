<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 12:31
 */
namespace Notadd\Foundation\Image\Drivers;
use Notadd\Foundation\Image\Contracts\SourceLoader as SourceLoaderContract;
/**
 * Class ImageSourceLoader
 * @package Notadd\Foundation\Image\Drivers
 */
class ImageSourceLoader implements SourceLoaderContract {
    /**
     * @var string
     */
    protected $tmp;
    /**
     * @var string
     */
    protected $file;
    /**
     * @var mixed
     */
    protected $source;
    /**
     * ImageSourceLoader constructor.
     */
    public function __construct() {
        $this->tmp = sys_get_temp_dir();
    }
    /**
     * @param string $url file source url
     * @throws \Thapp\Exception\ImageResourceLoaderException
     * @return string
     */
    public function load($url) {
        if(file_exists($url)) {
            return $this->validate($url);
        }
        if(preg_match('#^(https?|spdy)://#', $url)) {
            if($file = $this->loadRemoteFile($url)) {
                return $this->validate($file);
            }
        }
        throw new ImageResourceLoaderException(sprintf('Invalid Source URL: %s', $url));
    }
    /**
     * @param mixed $url
     * @access private
     * @return mixed
     */
    private function validate($url) {
        if(@getimagesize($url)) {
            $this->source = $url;
            return $url;
        }
        return false;
    }
    public function getSource() {
        return $this->source;
    }
    /**
     * @return void
     */
    public function __destruct() {
        $this->clean();
    }
    /**
     * @return void
     */
    public function clean() {
        if(file_exists($this->file)) {
            @unlink($this->file);
        }
    }
    /**
     * @param mixed $url
     * @return mixed
     */
    protected function loadRemoteFile($url) {
        $this->file = tempnam($this->tmp, 'jit_rmt_');
        if(!function_exists('curl_init')) {
            if(!$contents = file_get_contents($url)) {
                return false;
            }
            file_put_contents($contents, $this->file);
            return $this->file;
        }
        $handle = fopen($this->file, 'w');
        if(!$this->fetchFile($handle, $url)) {
            fclose($handle);
            return false;
        }
        fclose($handle);
        return $this->file;
    }
    /**
     * @param Resource $handle
     * @return mixed
     */
    protected function fetchFile($handle, $url, &$message = null) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FILE, $handle);
        $status = curl_exec($curl);
        $info = curl_getinfo($curl);
        if(!in_array($info['http_code'], [
            200,
            302,
            304
        ])
        ) {
            $status = false;
        }
        if(0 !== strlen($msg = curl_error($curl))) {
            $message = $msg;
            $status = false;
        }
        curl_close($curl);
        return $status;
    }
}