<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-21 15:15
 */
namespace Notadd\Foundation\SearchEngine;
use Illuminate\Support\Collection;
class Meta {
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $keywords;
    public function __construct() {
        $this->title = '{sitename}';
        $this->description = '{sitename}';
        $this->keywords = '{sitename}';
    }
    /**
     * @return static
     */
    public function getData() {
        $data = Collection::make();
        $data->put('title', $this->title);
        $data->put('description', $this->description);
        $data->put('keywords', $this->keywords);
        return $data;
    }
    /**
     * @param $title
     */
    public function setTitle($title) {
        $this->title = trim($title);
    }
    /**
     * @param $description
     */
    public function setDescription($description) {
        $this->description = trim($description);
    }
    /**
     * @param $keywords
     */
    public function setKeywords($keywords) {
        $this->keywords = trim($keywords);
    }
}