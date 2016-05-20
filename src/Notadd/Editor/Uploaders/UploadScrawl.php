<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-12 11:22
 */
namespace Notadd\Editor\Uploaders;
/**
 * Class UploadScrawl
 * @package Notadd\Editor\Uploaders
 */
class UploadScrawl extends AbstractUpload {
    /**
     * @return bool
     */
    public function doUpload() {
        $base64Data = $this->request->get($this->fileField);
        $img = base64_decode($base64Data);
        if(!$img) {
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_NOT_FOUND");
            return false;
        }
        $this->oriName = $this->config['oriName'];
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
        if(!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) {
            $this->filePath = str_replace($this->fileType, '.webp', $this->getFilePath());
            $this->image->make($this->getFilePath())->insert($this->config['watermark'], 'center')->save($this->filePath);
            $this->oriName = str_replace($this->fileType, '.webp', $this->oriName);
            $this->fileName = str_replace($this->fileType, '.webp', $this->fileName);
            $this->fullName = str_replace($this->fileType, '.webp', $this->fullName);
            $this->fileType = '.webp';
            $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
        } else {
            $this->stateInfo = $this->stateMap[0];
            return false;
        }
    }
    /**
     * @return string
     */
    protected function getFileExt() {
        return strtolower(strrchr($this->oriName, '.'));
    }
}