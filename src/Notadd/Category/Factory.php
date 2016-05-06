<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-06 14:57
 */
namespace Notadd\Category;
use Illuminate\Support\Collection;
use Notadd\Category\Models\Category as CategoryModel;
/**
 * Class Factory
 * @package Notadd\Category
 */
class Factory {
    /**
     * @param $id
     * @param $getAllSubCategories
     * @return \Illuminate\Support\Collection
     */
    public function handle($id, $getAllSubCategories) {
        $collections = new Collection();
        if($getAllSubCategories) {
            CategoryModel::getAllSubCategories($id, $collections);
        } else {
            $collections = CategoryModel::where('parent_id', $id)->get();
        }
        $collections->each(function(CategoryModel $model, $key) use($collections) {
            $collections->put($key, new Category($model->getAttribute('id')));
        });
        return $collections;
    }
}