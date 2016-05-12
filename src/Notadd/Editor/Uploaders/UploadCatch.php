<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-12 11:30
 */
namespace Notadd\Editor\Uploaders;
/**
 * Class UploadCatch
 * @package Notadd\Editor\Uploaders
 */
class UploadCatch extends AbstractUpload {
    /**
     * @return bool
     */
    public function doUpload() {
        $imgUrl = strtolower(str_replace("&amp;", "&", $this->config['imgUrl']));
        if(strpos($imgUrl, "http") !== 0) {
            $this->stateInfo = $this->getStateInfo("ERROR_HTTP_LINK");
            return false;
        }
        $heads = get_headers($imgUrl);
        if(!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
            $this->stateInfo = $this->getStateInfo("ERROR_DEAD_LINK");
            return false;
        }
        $fileType = strtolower(strrchr($imgUrl, '.'));
        if(!in_array($fileType, $this->config['allowFiles'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_HTTP_CONTENTTYPE");
            return false;
        }
        ob_start();
        $context = stream_context_create(array(
                'http' => array(
                    'follow_location' => false
                    // don't follow redirects
                )
            ));
        readfile($imgUrl, false, $context);
        $img = ob_get_contents();
        ob_end_clean();
        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);
        $this->oriName = $m ? $m[1] : "";
        $this->fileSize = strlen($img);
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = basename($this->filePath);
        $dirname = dirname($this->filePath);
        if(!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return false;
        }
        if(!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return false;
        } else if(!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return false;
        }
        if(!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
            return false;
        } else {
            $this->stateInfo = $this->stateMap[0];
            return true;
        }
    }
    /**
     * @return string
     */
    protected function getFileExt() {
        return strtolower(strrchr($this->oriName, '.'));
    }
}