<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-19 16:46
 */
namespace Notadd\Payment\Controllers\Admin;
use Illuminate\Http\Request;
use Notadd\Admin\Controllers\AbstractAdminController;
/**
 * Class PaymentController
 * @package Notadd\Payment\Controllers\Admin
 */
class PaymentController extends AbstractAdminController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->share('alipay_partner_id', $this->setting->get('payment.alipay.mch.id'));
        $this->share('alipay_partner_secret', $this->setting->get('payment.alipay.mch.secret'));
        $this->share('alipay_partner_email', $this->setting->get('payment.alipay.partner.email'));
        $this->share('alipay_return_url', $this->setting->get('payment.alipay.return.url'));
        $this->share('alipay_notify_url', $this->setting->get('payment.alipay.notify.url'));
        $this->share('wechat_app_id', $this->setting->get('payment.wechat.app.id'));
        $this->share('wechat_app_secret', $this->setting->get('payment.wechat.app.secret'));
        $this->share('wechat_mch_id', $this->setting->get('payment.wechat.mch.id'));
        $this->share('wechat_mch_secret', $this->setting->get('payment.wechat.mch.secret'));
        $this->share('wechat_notify_url', $this->setting->get('payment.wechat.notify.url'));
        return $this->view('payment.config');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $this->setting->set('payment.alipay.mch.id', $request->get('alipay_partner_id'));
        $this->setting->set('payment.alipay.mch.secret', $request->get('alipay_partner_secret'));
        $this->setting->set('payment.alipay.partner.email', $request->get('alipay_partner_email'));
        $this->setting->set('payment.alipay.return.url', $request->get('alipay_return_url'));
        $this->setting->set('payment.alipay.notify.url', $request->get('alipay_notify_url'));
        $this->setting->set('payment.wechat.app.id', $request->get('wechat_app_id'));
        $this->setting->set('payment.wechat.app.secret', $request->get('wechat_app_secret'));
        $this->setting->set('payment.wechat.mch.id', $request->get('wechat_mch_id'));
        $this->setting->set('payment.wechat.mch.secret', $request->get('wechat_mch_secret'));
        $this->setting->set('payment.wechat.notify.url', $request->get('wechat_notify_url'));
        return $this->redirect->back();
    }
}