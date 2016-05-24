<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-24 11:05
 */
namespace Notadd\Search\Controllers\Admin;
use Illuminate\Http\Request;
use Notadd\Admin\Controllers\AbstractAdminController;
/**
 * Class SearchController
 * @package Notadd\Search\Controllers\Admin
 */
class SearchController extends AbstractAdminController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->share('allow_baidu_zhannei', $this->setting->get('search.baidu.zhannei'));
        $this->share('baidu_zhannei_code', $this->setting->get('search.baidu.zhannei.code'));
        return $this->view('search.index');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $this->setting->set('search.baidu.zhannei', $request->get('allow_baidu_zhannei'));
        $this->setting->set('search.baidu.zhannei.code', $request->get('baidu_zhannei_code'));
        return $this->redirect->back();
    }
}