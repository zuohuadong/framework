<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 15:05
 */
namespace Notadd\Menu\Models;
use Illuminate\Container\Container;
use Notadd\Article\Models\Article;
use Notadd\Category\Models\Category;
use Notadd\Foundation\Database\Eloquent\Collection;
use Notadd\Foundation\Database\Eloquent\Model;
/**
 * Class Menu
 * @package Notadd\Menu\Models
 */
class Menu extends Model {
    /**
     * @var array
     */
    public $subItems = [];
    /**
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'group_id',
        'title',
        'tooltip',
        'link',
        'target',
        'foreground_color',
        'icon_image',
        'order_id',
        'enabled',
    ];
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
     * @param $group_id
     * @param $parent_id
     * @param $menus
     */
    public static function buildMenus($group_id, $parent_id, &$menus) {
        if(parent::whereEnabled(true)->whereGroupId($group_id)->whereParentId($parent_id)->count() > 0) {
            $data = parent::whereEnabled(true)->whereGroupId($group_id)->whereParentId($parent_id)->orderBy('order_id')->get();
            if($data->count() > 0) {
                foreach($data as $key => $value) {
                    if(strpos($value->link, 'http://') === false && strpos($value->link, 'https://') === false) {
                        $data[$key] = $value;
                    }
                    static::buildMenus($group_id, $value->id, $data[$key]->subItems);
                }
                $menus = $data;
            }
        }
    }
    /**
     * @return mixed
     */
    public function countSubMenu() {
        return parent::whereParentId($this->attributes['id'])->count();
    }
    /**
     * @return \Notadd\Foundation\Database\Eloquent\Relations\BelongsTo
     */
    public function group() {
        return $this->belongsTo(MenuGroup::class, 'group_id');
    }
    /**
     * @return mixed
     */
    public function getSubMenus() {
        return parent::whereEnabled(true)->whereGroupId($this->attributes['group_id'])->whereParentId($this->attributes['id'])->orderBy('order_id')->get();
    }
    /**
     * @return bool
     */
    public function isCurrentRoute() {
        if(strpos($this->attributes['link'], 'http://') === false && strpos($this->attributes['link'], 'https://') === false) {
            if(strpos($this->attributes['link'], 'category') !== false) {
                $tmp = trim($this->attributes['link'], '/');
                $tmp = explode('/', $tmp);
                if(is_numeric($tmp[1])) {
                    $id = $tmp[1];
                    $collection  = Collection::make();
                    $ids = [];
                    if(Container::getInstance()->make('request')->route("article")) {
                        $article = Article::find(Container::getInstance()->make('request')->route("article"));
                        if($article instanceof Article) {
                            Category::getAllParentCategories($article->category->id, $collection);
                        }
                        foreach($collection as $category) {
                            $ids[] = $category->id;
                        }
                        $ids = array_unique($ids);
                        if(in_array($id, $ids)) {
                            return true;
                        }
                    } else {
                        Category::getAllSubCategories($id, $collection);
                        $ids[] = $id;
                        foreach($collection as $category) {
                            $ids[] = $category->id;
                        }
                        $ids = array_unique($ids);
                        $current = Container::getInstance()->make('request')->route("category");
                        if(in_array($current, $ids)) {
                            return true;
                        }
                    }
                }
            } else {
                return Container::getInstance()->make('request')->is($this->attributes['link'] . '*') ? true : false;
            }
        }
    }
    /**
     * @return mixed
     */
    public function toUrl() {
        if(strpos($this->attributes['link'], 'http://') === false && strpos($this->attributes['link'], 'https://') === false) {
            return Container::getInstance()->make('url')->to($this->attributes['link']);
        } else {
            return $this->attributes['link'];
        }
    }
}