<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 15:08
 */
namespace Notadd\Category\Models;
use Illuminate\Support\Collection;
use Notadd\Article\Events\GetArticleAdminTemplates;
use Notadd\Article\Models\Article;
use Notadd\Category\Events\GetCategoryAdminTemplates;
use Notadd\Category\Events\GetCategoryTypes;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Category
 * @package Notadd\Category\Models
 */
class Category extends Model {
    /**
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'alias',
        'description',
        'type',
        'background_color',
        'seo_title',
        'seo_keyword',
        'seo_description',
        'background_image',
        'top_image',
        'enabled',
        'extend_id',
        'extend_type',
    ];
    /**
     * @var string
     */
    protected $showTemplate = 'themes::category.show';
    /**
     * @var array
     */
    public $subItems = [];
    /**
     * @param $parent_id
     * @param $crumb
     * @return mixed
     */
    public static function buildCrumb($parent_id, &$crumb) {
        if($parent_id == 0) {
            return $crumb;
        } else {
            $parent = parent::find($parent_id);
            array_unshift($crumb, $parent);
            if($parent['parent_id']) {
                static::buildCrumb($parent['parent_id'], $crumb);
            }
        }
    }
    /**
     * @param $parent_id
     * @param $categories
     */
    public static function buildCategories($parent_id, &$categories) {
        $data = parent::whereEnabled(true)->whereParentId($parent_id)->get();
        if($data->count() > 0) {
            foreach($data as $key => $value) {
                static::buildCategories($value->id, $data[$key]->subItems);
            }
            $categories = $data;
        }
    }
    public static function getAllParentCategories($id, Collection &$collections) {
        $category = parent::find($id);
        if($category instanceof self) {
            $collections->push($category);
            $parent = parent::where(['enabled' => true, 'id' => $category->parent_id])->first();
            if($parent instanceof self) {
                static::getAllParentCategories($parent->parent_id, $collections);
                $collections->push($parent);
            }
        }
    }
    /**
     * @param $parent_id
     * @param \Illuminate\Support\Collection $collections
     */
    public static function getAllSubCategories($parent_id, Collection &$collections) {
        $data = parent::whereEnabled(true)->whereParentId($parent_id)->get();
        if($data->count() > 0) {
            foreach($data as $value) {
                static::getAllSubCategories($value->id, $collections);
            }
            $collections = $collections->merge($data);
        }
    }
    /**
     * @param string $key
     * @return mixed|static
     */
    public function getAdminTemplate($key = '') {
        $templates = Collection::make();
        $templates->put('edit', 'admin::category.edit');
        static::$dispatcher->fire(new GetCategoryAdminTemplates($this, $templates));
        if($key) {
            return $templates->get($key);
        } else {
            return $templates;
        }
    }
    /**
     * @param string $key
     * @return mixed|static
     */
    public function getArticleTemplate($key = '') {
        $templates = Collection::make();
        $templates->put('create', 'admin::article.create');
        $templates->put('edit', 'admin::article.edit');
        $templates->put('list', 'admin::article.list');
        static::$dispatcher->fire(new GetArticleAdminTemplates($this, $templates));
        if($key) {
            return $templates->get($key);
        } else {
            return $templates;
        }
    }
    /**
     * @return string
     */
    public function getShowTemplate() {
        return $this->showTemplate;
    }
    /**
     * @param $template
     */
    public function setShowTemplate($template) {
        $this->showTemplate = $template;
    }
    /**
     * @return static
     */
    public function getTypes() {
        $types = Collection::make();
        $types->put('normal', '普通分类');
        static::$dispatcher->fire(new GetCategoryTypes($this, $types));
        return $types;
    }
    /**
     * @return bool
     */
    public function hasParent() {
        return $this->getAttribute('parent_id') && parent::whereId($this->getAttribute('parent_id'))->count();
    }
    /**
     * @return int
     */
    public function countArticles() {
        $count = Article::whereCategoryId($this->attributes['id'])->count();
        return $count ? $count : 0;
    }
    /**
     * @return int
     */
    public function countSubCategories() {
        $count = parent::whereParentId($this->attributes['id'])->count();
        return $count ? $count : 0;
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function extend() {
        return $this->morphTo();
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    /**
     * @param int $parent_id
     * @return static
     */
    public function getSubCategories($parent_id = 0) {
        if(!$parent_id) {
            $parent_id = $this->attributes['id'];
        }
        $count = parent::whereEnabled(true)->whereParentId($parent_id)->count();
        if($count) {
            return parent::whereEnabled(true)->whereParentId($parent_id)->get();
        } else {
            return Collection::make();
        }
    }
}