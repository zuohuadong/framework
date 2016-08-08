<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 15:46
 */
namespace Notadd\Category;
use Illuminate\Routing\Events\RouteMatched;
use Notadd\Category\Controllers\Admin\CategoryController as AdminCategoryController;
use Notadd\Category\Controllers\CategoryController;
use Notadd\Category\Listeners\BeforeCategoryDelete;
use Notadd\Category\Models\Category as CategoryModel;
use Notadd\Category\Observers\CategoryObserver;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
/**
 * Class CategoryServiceProvider
 * @package Notadd\Category
 */
class CategoryServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $categories = CategoryModel::whereEnabled(true)->get();
        foreach($categories as $value) {
            if($value->alias) {
                $category = new Category($value->id);
                $this->router->get($category->getRouting() . '/{id}', 'Notadd\Article\Controllers\ArticleController@show')->where('id', '[0-9]+');
                $this->router->get($category->getRouting(), function() use ($category) {
                    return $this->app->call(CategoryController::class . '@show', ['id' => $category->getId()]);
                });
            }
        }
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->router->resource('category', AdminCategoryController::class);
            $this->router->get('category/{id}/move', AdminCategoryController::class . '@move');
            $this->router->post('category/{id}/moving', AdminCategoryController::class . '@moving');
            $this->router->post('category/{id}/status', AdminCategoryController::class . '@status');
        });
        $this->router->resource('category', CategoryController::class);
        $this->events->subscribe(BeforeCategoryDelete::class);
        $this->events->listen(RouteMatched::class, function () {
            $this->view->share('__category', $this->app->make(Factory::class));
        });
        $this->blade->directive('category', function($expression) {
            $segments = explode(',', preg_replace("/[\(\)\\\"\']/", '', $expression));
            return "<?php \$__tmp = \$__category->handle(" . trim($segments[0]) . ", " . trim($segments[1]) ."); foreach(\$__tmp as \$" . trim($segments[2]) . "=>\$" . trim($segments[3]) . "): ?>";
        });
        $this->blade->directive('endcategory', function($expression) {
            return "<?php endforeach; ?>";
        });
        CategoryModel::observe(CategoryObserver::class);
    }
    /**
     * @return void
     */
    public function register() {
    }
}