<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-12 17:22
 */
namespace Notadd\Flash;
use Notadd\Flash\Models\FlashItem;
/**
 * Class Flash
 * @package Notadd\Flash
 */
class Flash {
    /**
     * @var int
     */
    private $id;
    /**
     * @var \Notadd\Flash\Models\FlashItem
     */
    private $model;
    /**
     * Flash constructor.
     * @param $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->model = FlashItem::findOrFail($id);
    }
    /**
     * @return string
     */
    public function getTitle() {
        return $this->model->getAttribute('title');
    }
    /**
     * @return string
     */
    public function getLink() {
        return $this->model->getAttribute('link');
    }
    /**
     * @return string
     */
    public function getLinkTarget() {
        return $this->model->getAttribute('link_target');
    }
    /**
     * @return string
     */
    public function getAltInfo() {
        return $this->model->getAttribute('alt_info');
    }
    /**
     * @return string
     */
    public function getThumbImage() {
        return $this->model->getAttribute('thumb_image');
    }
    /**
     * @return string
     */
    public function getFullImage() {
        return $this->model->getAttribute('full_image');
    }
}