<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 15:54
 */
namespace Notadd\Payment;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Notadd\Payment\Controllers\Admin\PaymentController as AdminPaymentController;
use Notadd\Payment\Controllers\NotifyController;
use Notadd\Payment\Factories\Gateway;
/**
 * Class PaymentServiceProvider
 * @package Notadd\Payment
 */
class PaymentServiceProvider extends AbstractServiceProvider {
    /**
     * @var bool
     */
    protected $defer = false;
    /**
     * @return void
     */
    public function boot() {
        $this->config->set('pay', [
            'default' => 'alipay',
            'gateways' => [
                'alipay' => [
                    'driver'  => 'Alipay_Express',
                    'options' => [
                        'partner' => $this->setting->get('payment.alipay.mch.id'),
                        'key' => $this->setting->get('payment.alipay.mch.secret'),
                        'sellerEmail' => $this->setting->get('payment.alipay.partner.email'),
                        'returnUrl' => $this->setting->get('payment.alipay.return.url'),
                        'notifyUrl' => $this->setting->get('payment.alipay.notify.url'),
                    ]
                ],
                'wechatpay' => [
                    'driver' => 'Wechatpay_Native',
                    'options' => [
                        'appid' => $this->setting->get('payment.wechat.app.id'),
                        'api_key' => $this->setting->get('payment.wechat.app.secret'),
                        'mch_id' => $this->setting->get('payment.wechat.mch.id'),
                        'mch_key' => $this->setting->get('payment.wechat.mch.secret'),
                        'notify_url' => $this->setting->get('payment.wechat.notify.url')
                    ]
                ]
            ]
        ]);
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function() {
            $this->router->resource('payment', AdminPaymentController::class);
        });
        $this->router->any('notify/{type}', NotifyController::class . '@handle');
    }
    /**
     * @return array
     */
    public function provides() {
        return ['pay'];
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('pay', function($app) {
            $factory = new Gateway();
            return new PaymentManager($app, $factory);
        });
    }
}