<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-18 11:55
 */
namespace Notadd\Sitemap;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Notadd\Sitemap\Models\Sitemap as Model;
/**
 * Class Sitemap
 * @package Notadd\Sitemap
 */
class Sitemap {
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;
    /**
     * @var \Illuminate\Cache\CacheManager
     */
    private $cache;
    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $file;
    /**
     * @var \Illuminate\Contracts\Logging\Log
     */
    protected $log;
    /**
     * @var Model $model
     */
    public $model = null;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;
    /**
     * Sitemap constructor.
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param array $config
     */
    public function __construct(Application $app, array $config) {
        $this->app = $app;
        $this->cache = $this->app->make('cache');
        $this->config = $this->app->make('config');
        $this->file = $this->app->make('files');
        $this->log = $this->app->make('log');
        $this->model = new Model($config);
        $this->view = $this->app->make('view');
    }
    /**
     * @param string $key
     * @param int $duration
     * @param boolean $useCache
     */
    public function setCache($key = null, $duration = null, $useCache = true) {
        $this->model->setUseCache($useCache);
        if($key !== null) {
            $this->model->setCacheKey($key);
        }
        if($duration !== null) {
            $this->model->setCacheDuration($duration);
        }
    }
    /**
     * @param string $loc
     * @param string $lastmod
     * @param string $priority
     * @param string $freq
     * @param array $images
     * @param string $title
     * @param array $translations
     * @param array $videos
     * @return void
     */
    public function add($loc, $lastmod = null, $priority = null, $freq = null, $images = [], $title = null, $translations = [], $videos = []) {
        if($this->model->getEscaping()) {
            $loc = htmlentities($loc, ENT_XML1);
            if($title != null)
                htmlentities($title, ENT_XML1);
            if($images) {
                foreach($images as $k => $image) {
                    foreach($image as $key => $value) {
                        $images[$k][$key] = htmlentities($value, ENT_XML1);
                    }
                }
            }
            if($translations) {
                foreach($translations as $k => $translation) {
                    foreach($translation as $key => $value) {
                        $translations[$k][$key] = htmlentities($value, ENT_XML1);
                    }
                }
            }
            if($videos) {
                foreach($videos as $k => $video) {
                    if($video['title'])
                        $videos[$k]['title'] = htmlentities($video['title'], ENT_XML1);
                    if($video['description'])
                        $videos[$k]['description'] = htmlentities($video['description'], ENT_XML1);
                }
            }
        }
        $this->model->setItems([
            'loc' => $loc,
            'lastmod' => $lastmod,
            'priority' => $priority,
            'freq' => $freq,
            'images' => $images,
            'title' => $title,
            'translations' => $translations,
            'videos' => $videos
        ]);
    }
    /**
     * @param string $loc
     * @param string $lastmod
     * @return void
     */
    public function addSitemap($loc, $lastmod = null) {
        $this->model->setSitemaps([
            'loc' => $loc,
            'lastmod' => $lastmod,
        ]);
    }
    /**
     * @param string $format
     * @return mixed
     */
    public function render($format = 'xml') {
        $data = $this->generate($format);
        if($format == 'html') {
            return $data['content'];
        }
        return $this->app->make(ResponseFactory::class)->make($data['content'], 200, $data['headers']);
    }
    /**
     * @param string $format (options: xml, html, txt, ror-rss, ror-rdf, sitemapindex)
     * @return array
     */
    public function generate($format = 'xml') {
        if($this->isCached()) {
            ($format == 'sitemapindex') ? $this->model->sitemaps = $this->cache->get($this->model->getCacheKey()) : $this->model->items = $this->cache->get($this->model->getCacheKey());
        } elseif($this->model->getUseCache()) {
            ($format == 'sitemapindex') ? $this->cache->put($this->model->getCacheKey(), $this->model->getSitemaps(), $this->model->getCacheDuration()) : $this->cache->put($this->model->getCacheKey(), $this->model->getItems(), $this->model->getCacheDuration());
        }
        if(!$this->model->getLink()) {
            $this->model->setLink($this->config->get('app.url'));
        }
        if(!$this->model->getTitle()) {
            $this->model->setTitle('Sitemap for ' . $this->model->getLink());
        }
        $channel = [
            'title' => $this->model->getTitle(),
            'link' => $this->model->getLink(),
        ];
        switch($format) {
            case 'ror-rss':
                return [
                    'content' => $this->view->make('default::sitemap.ror-rss', [
                        'items' => $this->model->getItems(),
                        'channel' => $channel
                    ])->render(),
                    'headers' => ['Content-type' => 'text/rss+xml; charset=utf-8']
                ];
            case 'ror-rdf':
                return [
                    'content' => $this->view->make('default::sitemap.ror-rdf', [
                        'items' => $this->model->getItems(),
                        'channel' => $channel
                    ])->render(),
                    'headers' => ['Content-type' => 'text/rdf+xml; charset=utf-8']
                ];
            case 'html':
                return [
                    'content' => $this->view->make('default::sitemap.html', [
                        'items' => $this->model->getItems(),
                        'channel' => $channel
                    ])->render(),
                    'headers' => ['Content-type' => 'text/html']
                ];
            case 'txt':
                return [
                    'content' => $this->view->make('default::sitemap.txt', ['items' => $this->model->getItems()])->render(),
                    'headers' => ['Content-type' => 'text/plain']
                ];
            case 'sitemapindex':
                return [
                    'content' => $this->view->make('default::sitemap.sitemapindex', ['sitemaps' => $this->model->getSitemaps()])->render(),
                    'headers' => ['Content-type' => 'text/xml; charset=utf-8']
                ];
            default:
                return [
                    'content' => $this->view->make('default::sitemap.' . $format, ['items' => $this->model->getItems(), 'top' => '<?xml version="1.0" encoding="UTF-8"?>'])->render(),
                    'headers' => ['Content-type' => 'text/xml; charset=utf-8']
                ];
        }
    }
    /**
     * @param string $format
     * @param string $filename
     * @return void
     */
    public function store($format = 'xml', $filename = 'sitemap') {
        if(count($this->model->getItems()) > 50000) {
            foreach(array_chunk($this->model->getItems(), 50000) as $key => $item) {
                $this->model->items = $item;
                $this->store('xml', $filename . '-' . $key);
                $this->addSitemap(url($filename . '-' . $key . '.xml'));
            }
            $data = $this->generate('sitemapindex');
        } else {
            $data = $this->generate($format);
        }
        if($format == 'ror-rss' || $format == 'ror-rdf' || $format == 'sitemapindex') {
            $format = 'xml';
        }
        $file = public_path() . DIRECTORY_SEPARATOR . $filename . '.' . $format;
        $this->file->put($file, $data['content']);
        ($format == 'sitemapindex') ? $this->model->sitemaps = [] : $this->model->items = [];
    }
    /**
     * @return bool
     */
    public function isCached() {
        if($this->model->getUseCache()) {
            if($this->cache->has($this->model->getCacheKey())) {
                return true;
            }
        }
        return false;
    }
}