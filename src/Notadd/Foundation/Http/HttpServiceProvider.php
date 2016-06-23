<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-02 17:55
 */
namespace Notadd\Foundation\Http;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Notadd\Foundation\SearchEngine\Optimization;
use Notadd\Foundation\Traits\InjectRouterTrait;
use Notadd\Foundation\Traits\InjectSettingTrait;
use Notadd\Page\Models\Page;
/**
 * Class HttpServiceProvider
 * @package Notadd\Foundation\Http
 */
class HttpServiceProvider extends ServiceProvider {
    use InjectSettingTrait, InjectRouterTrait;
    /**
     * @return void
     */
    public function boot() {
        switch($this->getSetting()->get('', 0)) {
            case 1:
                $this->app->make('url')->forceSchema('https');
                break;
            case 2:
                $this->app->make('url')->forceSchema('http');
                break;
            default;
                break;
        }
        $this->getRouter()->get('/', function() {
            $home = $this->getSetting()->get('site.home', 'default');
            $page_id = 0;
            if($home != 'default' && Str::contains($home, 'page_')) {
                $page_id = Str::substr($home, 5);
            }
            if($page_id && Page::whereEnabled(true)->whereId($page_id)->count()) {
                return $this->app->call('Notadd\Page\Controllers\PageController@show', ['id' => $page_id]);
            }
            $this->app->make('view')->share('logo', file_get_contents(realpath($this->app->frameworkPath() . '/views/install') . DIRECTORY_SEPARATOR . 'logo.svg'));
            $this->app->make(Optimization::class)->setDescriptionMeta($this->getSetting()->get('seo.description'));
            $this->app->make(Optimization::class)->setKeywordsMeta($this->getSetting()->get('seo.keyword'));
            return $this->app->make('view')->make('themes::index');
        });
    }
    /**
     * @return void
     */
    public function register() {
    }
}