<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-10 14:51:12
 */
namespace Notadd\Category;
use Illuminate\Support\Collection;
use Notadd\Article\Article;
use Notadd\Article\Models\Article as ArticleModel;
use Notadd\Category\Models\Category as CategoryModel;
/**
 * Class Category
 * @package Notadd\Category
 */
class Category {
    /**
     * @var int
     */
    private $id;
    /**
     * @var
     */
    private $links;
    /**
     * @var \Illuminate\Support\Collection
     */
    private $list;
    /**
     * @var \Notadd\Category\Models\Category
     */
    private $model;
    /**
     * @var \Illuminate\Support\Collection
     */
    private $relations;
    /**
     * Category constructor.
     * @param $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->model = CategoryModel::findOrFail($id);
    }
    /**
     * @return mixed
     */
    public function getBackgroundImage() {
        return $this->model->getAttribute('background_image');
    }
    /**
     * @return string
     */
    public function getDescription() {
        return $this->model->getAttribute('seo_description');
    }
    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getLinks() {
        return $this->links;
    }
    /**
     * @param $links
     */
    public function setLinks($links) {
        $this->links = $links;
    }
    /**
     * @return static
     */
    public function getList() {
        if(!isset($this->list)) {
            if($this->model->hasParent()) {
                $model = ArticleModel::whereCategoryId($this->model->getAttribute('id'))->orderBy('created_at', 'desc');
            } else {
                $relations = $this->getRelationCategoryList();
                $list = Collection::make();
                $list->push($this->model->getAttribute('id'));
                foreach($relations as $relation) {
                    $list->push($relation->getId());
                }
                $model = ArticleModel::whereIn('category_id', $list->toArray())->orderBy('created_at', 'desc');
            }
            $data = $model->paginate(15);
            $this->links = $data->links();
            $list = Collection::make();
            foreach($data as $value) {
                $list->push(new Article($value->getAttribute('id')));
            }
            $this->list = $list;
        }
        return $this->list;
    }
    /**
     * @param \Illuminate\Support\Collection $list
     */
    public function setList(Collection $list) {
        $this->list = $list;
    }
    /**
     * @param \Illuminate\Support\Collection $list
     * @param \Notadd\Category\Models\Category|null $model
     */
    public function getLoopParent(Collection &$list, CategoryModel $model = null) {
        if($model === null) {
            $model = $this->model;
        }
        if($model->hasParent()) {
            $parent = $model->getAttribute('parent');
            $list->prepend($parent);
            $this->getLoopParent($list, $parent);
        }
    }
    /**
     * @return string
     */
    public function getKeywords() {
        return $this->model->getAttribute('seo_keyword');
    }
    /**
     * @return \Notadd\Category\Models\Category
     */
    public function getModel() {
        return $this->model;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getRelationCategoryList() {
        if(!isset($this->relations)) {
            $list = Collection::make();
            if($this->model->hasParent()) {
                $data = $this->model->whereEnabled(true)->whereParentId($this->model->getAttribute('parent_id'))->orderBy('created_at', 'asc')->get();
            } else {
                $data = $this->model->whereEnabled(true)->whereParentId($this->model->getAttribute('id'))->orderBy('created_at', 'asc')->get();
            }
            if($data->count()) {
                foreach($data as $category) {
                    $list->push(new Category($category->getAttribute('id')));
                }
            } else {
                $list->push(new Category($this->model->getAttribute('id')));
            }
            $this->relations = $list;
        }
        return $this->relations;
    }
    /**
     * @param \Illuminate\Support\Collection $list
     */
    public function setRelationCategoryList(Collection $list) {
        $this->relations = $list;
    }
    /**
     * @return string
     */
    public function getRouting() {
        if($this->model->getAttribute('alias')) {
            $loopParent = Collection::make([$this->model]);
            $this->getLoopParent($loopParent);
            $routingString = Collection::make();
            foreach($loopParent as $model) {
                $model->getAttribute('alias') && $routingString->push($model->getAttribute('alias'));
            }
            return $routingString->implode('/');
        } else {
            return 'category/' . $this->id;
        }
    }
    /**
     * @return string
     */
    public function getShowTemplate() {
        return $this->model->getShowTemplate();
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getSubCategories() {
        $collections = new Collection();
        $data = $this->model->where('parent_id', $this->model->getAttribute('id'))->get();
        $data->each(function(CategoryModel $model) use($collections) {
            $collections->push(new Category($model->getAttribute('id')));
        });
        return $collections;
    }
    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->model->getAttribute('title');
    }
    /**
     * @return mixed
     */
    public function getTopImage() {
        return $this->model->getAttribute('top_image');
    }
    /**
     * @return mixed
     */
    public function getType() {
        return $this->model->getAttribute('type');
    }
}