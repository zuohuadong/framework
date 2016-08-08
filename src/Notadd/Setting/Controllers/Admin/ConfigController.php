<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 11:21
 */
namespace Notadd\Setting\Controllers\Admin;
use Illuminate\Filesystem\Filesystem;
use Notadd\Admin\Controllers\AbstractAdminController;
use Notadd\Page\Models\Page;
use Notadd\Setting\Requests\SeoRequest;
use Notadd\Setting\Requests\SiteRequest;
/**
 * Class ConfigController
 * @package Notadd\Setting\Controllers\Admin
 */
class ConfigController extends AbstractAdminController {
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $file;
    /**
     * ConfigController constructor.
     * @param \Illuminate\Filesystem\Filesystem $file
     */
    public function __construct(Filesystem $file) {
        parent::__construct();
        $this->file = $file;
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function getSite() {
        $this->share('title', $this->setting->get('site.title'));
        $this->share('domain', $this->setting->get('site.domain'));
        $this->share('beian', $this->setting->get('site.beian'));
        $this->share('email', $this->setting->get('site.email'));
        $this->share('statistics', $this->setting->get('site.statistics'));
        $this->share('copyright', $this->setting->get('site.copyright'));
        $this->share('company', $this->setting->get('site.company'));
        $this->share('message', $this->session->get('message'));
        $this->share('debug', $this->setting->get('site.debug'));
        $this->share('scheme', $this->setting->get('site.scheme', '0'));
        $this->share('home', $this->setting->get('site.home'));
        $this->share('pages', Page::all());
        return $this->view('config.site');
    }
    /**
     * @param \Notadd\Setting\Requests\SiteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSite(SiteRequest $request) {
        $this->setting->set('site.title', $request->get('title'));
        $this->setting->set('site.domain', $request->get('domain'));
        $this->setting->set('site.beian', $request->get('beian'));
        $this->setting->set('site.email', $request->get('email'));
        $this->setting->set('site.statistics', $request->get('statistics'));
        $this->setting->set('site.copyright', $request->get('copyright'));
        $this->setting->set('site.company', $request->get('company'));
        $this->setting->set('site.debug', $request->get('debug'));
        $this->setting->set('site.scheme', $request->get('scheme'));
        $this->setting->set('site.home', $request->get('home'));
        if($request->get('debug')) {
            touch($this->app->storagePath() . '/notadd/debug');
        } else {
            $this->file->delete($this->app->storagePath() . '/notadd/debug');
        }
        return $this->redirect->to('admin/site')->withMessage('更新站点信息成功');
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function getSeo() {
        $this->share('title', $this->setting->get('seo.title'));
        $this->share('keyword', $this->setting->get('seo.keyword'));
        $this->share('description', $this->setting->get('seo.description'));
        $this->share('message', $this->session->get('message'));
        return $this->view('config.seo');
    }
    /**
     * @param \Notadd\Setting\Requests\SeoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSeo(SeoRequest $request) {
        $this->setting->set('seo.title', $request->get('title'));
        $this->setting->set('seo.keyword', $request->get('keyword'));
        $this->setting->set('seo.description', $request->get('description'));
        $this->share('message', '更新SEO设置成功');
        return $this->redirect->to('admin/seo');
    }
}