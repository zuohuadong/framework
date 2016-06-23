<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 17:14
 */
namespace Notadd\Page;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory as View;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Notadd\Article\Article;
use Notadd\Article\Models\Article as ArticleModel;
use Notadd\Article\Models\ArticleRecommend;
use Notadd\Flash\Models\FlashItem;
use Notadd\Page\Models\Page as Model;
/**
 * Class Factory
 * @package Notadd\Page
 */
class Factory {
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $file;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;
    /**
     * Factory constructor.
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Illuminate\Filesystem\Filesystem $file
     * @param \Illuminate\Contracts\View\Factory $view
     */
    public function __construct(Repository $config, Filesystem $file, View $view) {
        $this->config = $config;
        $this->file = $file;
        $this->view = $view;
    }
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all() {
        return Model::all();
    }
    /**
     * @param $category
     * @param $limit
     * @return \Illuminate\Support\Collection
     */
    public function article($category, $limit) {
        $articles = new Collection();
        if(Str::contains($category, '-')) {
            $categories = explode('-', $category);
            $data = ArticleModel::whereIn('category_id', $categories)->limit($limit)->orderBy('created_at', 'desc')->get();
        } else {
            $data = ArticleModel::whereCategoryId($category)->limit($limit)->orderBy('created_at', 'desc')->get();
        }
        foreach($data as $item) {
            $id = $item->getAttribute('id');
            $article = new Article($id);
            $articles->push($article);
        }
        return $articles;
    }
    /**
     * @param $type
     * @param array $opinions
     * @return null|void
     */
    public function call($type, $opinions = []) {
        switch($type) {
            case "ad":
                return $this->callAd($opinions);
            case "article":
                return $this->callArticle($opinions);
            case "flash":
                return $this->callFlash($opinions);
            default:
                return null;
        }
    }
    /**
     * @param array $opinions
     * @return string
     */
    public function callAd($opinions = []) {
        return '';
    }
    /**
     * @param array $opinions
     * @return mixed
     */
    public function callArticle($opinions = []) {
        $articles = Collection::make();
        if(isset($opinions['category'])) {
            if(is_array($opinions['category'])) {
                $articles = ArticleModel::whereIn('category_id', $opinions['category']);
            } else {
                $articles = ArticleModel::whereCategoryId($opinions['category']);
            }
            if(isset($opinions['offset']) && $opinions['offset'] > 0) {
                $articles->skip($opinions['offset']);
            }
            if(isset($opinions['limit']) && $opinions['limit'] > 0) {
                $articles->take($opinions['limit']);
            }
            $articles = $articles->get();
            if(isset($opinions['thumbnail']) && is_array($opinions['thumbnail'])) {
                foreach($articles as $key=>$article) {
                    preg_match_all("/<img([^>]*)\s*src=('|\")([^'\"]+)('|\")/", $article->content, $matches);
                    $hash = '';
                    $thumbnail = '';
                    if($matches && $matches[3]) {
                        $matches = array_unique($matches[3]);
                        if($matches[0] && $this->file->exists(public_path($matches[0]))) {
                            $thumbnail = $matches[0];
                            $hash = hash_file('md5', public_path($matches[0]), false);
                        }
                    }
                    if($thumbnail && $hash) {
                        $path = '/uploads/thumbnails/' . $opinions['thumbnail']['width'] . 'X' . $opinions['thumbnail']['height'] . '/' .$hash . '.' . $this->file->extension(public_path($thumbnail));
                        $directory = public_path('/uploads/thumbnails/' . $opinions['thumbnail']['width'] . 'X' . $opinions['thumbnail']['height'] . '/');
                        if(!$this->file->isDirectory($directory)) {
                            $this->file->makeDirectory($directory, 0777, true, true);
                        }
                        if(!$this->file->exists(public_path($path))) {
                            $image = Image::make($thumbnail, $opinions['thumbnail']);
                            $image->save(public_path($path));
                        }
                        $article->thumbnail = $path;
                    }
                    $articles->put($key, $article);
                }
            }
        }
        return $articles;
    }
    /**
     * @param array $opinions
     */
    public function callFlash($opinions = []) {
        $group = $opinions['group'];
        $flashes = FlashItem::whereGroupId($group)->get();
        return $flashes;
    }
    /**
     * @param $id
     * @return Page
     */
    public function make($id) {
        return new Page($id);
    }
    /**
     * @param $key
     * @param array $opinions
     * @return mixed
     */
    public function position($key, $opinions = []) {
        $config = $this->config->get('page.recommends');
        $config = array_merge($config[$key], $opinions);
        $articles = ArticleRecommend::wherePosition($key);
        if(isset($opinions['limit']) && $opinions['limit'] > 0) {
            $articles->take($config['limit']);
        }
        $articles = $articles->orderBy('created_at', 'desc')->get();
        if(isset($config['thumbnail']) && is_array($config['thumbnail'])) {
            foreach($articles as $k=>$article) {
                $article = ArticleModel::find($article->article_id);
                if($article) {
                    preg_match_all("/<img([^>]*)\s*src=('|\")([^'\"]+)('|\")/", $article->content, $matches);
                    $hash = '';
                    $thumbnail = '';
                    if($matches && $matches[3]) {
                        $matches = array_unique($matches[3]);
                        if($matches[0] && $this->file->exists(public_path($matches[0]))) {
                            $thumbnail = $matches[0];
                            $hash = hash_file('md5', public_path($matches[0]), false);
                        }
                    }
                    if($thumbnail && $hash) {
                        $path = '/uploads/thumbnails/' . $config['thumbnail']['width'] . 'X' . $config['thumbnail']['height'] . '/' .$hash . '.' . $this->file->extension(public_path($thumbnail));
                        $directory = public_path('/uploads/thumbnails/' . $config['thumbnail']['width'] . 'X' . $config['thumbnail']['height'] . '/');
                        if(!$this->file->isDirectory($directory)) {
                            $this->file->makeDirectory($directory, 0777, true, true);
                        }
                        if(!$this->file->exists(public_path($path))) {
                            $image = Image::make($thumbnail, $config['thumbnail']);
                            $image->save(public_path($path));
                        }
                        $article->thumbnail = $path;
                    }
                    $articles->put($k, $article);
                } else {
                    $articles->forget($k);
                }
            }
        }
        return $this->view->make($config['template'])->withArticles($articles);
    }
}