<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-02 16:29
 */
namespace Notadd\Article\Events;
use Illuminate\Support\Collection;
use Notadd\Category\Models\Category;
/**
 * Class GetArticleAdminTemplates
 * @package Notadd\Article\Events
 */
class GetArticleAdminTemplates {
    /**
     * @var \Notadd\Category\Models\Category
     */
    private $category;
    /**
     * @var \Illuminate\Support\Collection
     */
    private $templates;
    /**
     * GetArticleAdminTemplates constructor.
     * @param \Notadd\Category\Models\Category $category
     * @param \Illuminate\Support\Collection $templates
     */
    public function __construct(Category $category, Collection $templates) {
        $this->category = $category;
        $this->templates = $templates;
    }
    /**
     * @return \Notadd\Category\Models\Category
     */
    public function getCategory() {
        return $this->category;
    }
    /**
     * @param $key
     * @param $value
     */
    public function register($key, $value) {
        $this->templates->put($key, $value);
    }
}