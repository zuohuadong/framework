<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-25 16:22
 */
namespace Notadd\Auth\Controllers\Admin;
use Illuminate\Http\Request;
use Notadd\Foundation\Abstracts\AbstractAdminController;
/**
 * Class ConfigController
 * @package Notadd\Auth\Controllers\Admin
 */
class ThirdController extends AbstractAdminController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->share('third_qq_enable', $this->setting->get('third.qq.enable'));
        $this->share('third_qq_key', $this->setting->get('third.qq.key'));
        $this->share('third_qq_secret', $this->setting->get('third.qq.secret'));
        $this->share('third_qq_callback', $this->setting->get('third.qq.callback'));
        $this->share('third_weibo_enable', $this->setting->get('third.weibo.enable'));
        $this->share('third_weibo_key', $this->setting->get('third.weibo.key'));
        $this->share('third_weibo_secret', $this->setting->get('third.weibo.secret'));
        $this->share('third_weibo_callback', $this->setting->get('third.weibo.callback'));
        $this->share('third_weixin_enable', $this->setting->get('third.weixin.enable'));
        $this->share('third_weixin_key', $this->setting->get('third.weixin.key'));
        $this->share('third_weixin_secret', $this->setting->get('third.weixin.secret'));
        $this->share('third_weixin_callback', $this->setting->get('third.weixin.callback'));
        return $this->view('auth.third');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $this->setting->set('third.qq.enable', $request->get('third_qq_enable'));
        $this->setting->set('third.qq.key', $request->get('third_qq_key'));
        $this->setting->set('third.qq.secret', $request->get('third_qq_secret'));
        $this->setting->set('third.qq.callback', $request->get('third_qq_callback'));
        $this->setting->set('third.weibo.enable', $request->get('third_weibo_enable'));
        $this->setting->set('third.weibo.key', $request->get('third_weibo_key'));
        $this->setting->set('third.weibo.secret', $request->get('third_weibo_secret'));
        $this->setting->set('third.weibo.callback', $request->get('third_weibo_callback'));
        $this->setting->set('third.weixin.enable', $request->get('third_weixin_enable'));
        $this->setting->set('third.weixin.key', $request->get('third_weixin_key'));
        $this->setting->set('third.weixin.secret', $request->get('third_weixin_secret'));
        $this->setting->set('third.weixin.callback', $request->get('third_weixin_callback'));
        return $this->redirect->back();
    }
}