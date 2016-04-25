<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 17:16
 */
namespace Notadd\Page;
use Illuminate\Support\Collection;
use Notadd\Page\Models\Page as PageModel;
/**
 * Class Page
 * @package Notadd\Page
 */
class Page {
    /**
     * @var int
     */
    private $id;
    /**
     * @var \Notadd\Page\Models\Page
     */
    protected $model;
    /**
     * Page constructor.
     * @param $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->model = PageModel::findOrFail($id);
    }
    /**
     * @return mixed
     */
    public function getAlias() {
        return $this->model->getAttribute('alias');
    }
    /**
     * @return mixed
     */
    public function getContent() {
        return $this->model->getAttribute('content');
    }
    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->model->getAttribute('description');
    }
    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getKeywords() {
        return $this->model->getAttribute('keyword');
    }
    /**
     * @param \Illuminate\Support\Collection $list
     * @param \Notadd\Page\Models\Page|null $model
     */
    public function getLoopParent(Collection &$list, PageModel $model = null) {
        if ($model === null) {
            $model = $this->model;
        }
        if ($model->hasParent()) {
            $parent = $model->getAttribute('parent');
            $list->prepend($parent);
            $this->getLoopParent($list, $parent);
        }
    }
    /**
     * @return mixed
     */
    public function getModel() {
        return $this->model;
    }
    /**
     * @return mixed
     */
    public function getPageId() {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getRouting() {
        $loopParent = Collection::make([$this->model]);
        $this->getLoopParent($loopParent);
        $routingString = Collection::make();
        foreach ($loopParent as $model) {
            $model->getAttribute('alias') && $routingString->push($model->getAttribute('alias'));
        }
        return $routingString->implode('/');
    }
    /**
     * @return static
     */
    public function getSubPages() {
        $list = Collection::make();
        $data = $this->model->whereParentId($this->model->getAttribute('id'))->get();
        foreach($data as $value) {
            $list->push(new Page($value->getAttribute('id')));
        }
        return $list;
    }
    /**
     * @return string
     */
    public function getTemplate() {
        return $this->model->getAttribute('template');
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
    public function getTitle() {
        return $this->model->getAttribute('title');
    }
    /**
     * @param $id
     * @param $model
     */
    public function replaceSelf($id, $model) {
        $this->id = $id;
        $this->model = $model;
    }
}