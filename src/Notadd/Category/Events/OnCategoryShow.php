<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Category\Events;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Notadd\Category\Category;
/**
 * Class OnCategoryShow
 * @package Notadd\Category\Events
 */
class OnCategoryShow {
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private $application;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $view;
    /**
     * @var \Notadd\Category\Models\Category
     */
    private $category;
    /**
     * OnCategoryShow constructor.
     * @param \Illuminate\Contracts\Foundation\Application $application
     * @param \Illuminate\Contracts\View\Factory $view
     * @param \Notadd\Category\Category $category
     */
    public function __construct(Application $application, Factory $view, Category $category) {
        $this->application = $application;
        $this->view = $view;
        $this->category = $category;
    }
    /**
     * @return \Notadd\Category\Category
     */
    public function getCategory() {
        return $this->category;
    }
    /**
     * @return \Notadd\Category\Models\Category
     */
    public function getCategoryModel() {
        return $this->getCategoryModel();
    }
    /**
     * @param $template
     */
    public function setCategoryShowTemplate($template) {
        $this->category->getModel()->setShowTemplate($template);
    }
    /**
     * @param $key
     * @param $value
     */
    public function share($key, $value) {
        $this->view->share($key, $value);
    }
}