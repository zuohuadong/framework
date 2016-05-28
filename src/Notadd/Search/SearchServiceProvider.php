<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-21 14:49
 */
namespace Notadd\Search;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Traits\InjectRouterTrait;
use Notadd\Search\Controllers\Admin\SearchController as AdminSearchController;
/**
 * Class SearchServiceProvider
 * @package Notadd\Search
 */
class SearchServiceProvider extends ServiceProvider {
    use InjectRouterTrait;
    /**
     * @return void
     */
    public function boot() {
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function() {
            $this->getRouter()->resource('search', AdminSearchController::class);
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