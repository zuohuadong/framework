<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-21 14:49
 */
namespace Notadd\Search;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Notadd\Search\Controllers\Admin\SearchController as AdminSearchController;
/**
 * Class SearchServiceProvider
 * @package Notadd\Search
 */
class SearchServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function() {
            $this->router->resource('search', AdminSearchController::class);
        });
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('search', function() {
            return $this->app->make(Factory::class);
        });
    }
}