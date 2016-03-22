<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 15:54
 */
namespace Notadd\Payment;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Traits\InjectConfigTrait;
use Notadd\Foundation\Traits\InjectRouterTrait;
use Notadd\Foundation\Traits\InjectSettingTrait;
use Notadd\Payment\Controllers\Admin\PaymentController as AdminPaymentController;
use Notadd\Payment\Controllers\NotifyController;
use Notadd\Payment\Factories\Gateway;
/**
 * Class PaymentServiceProvider
 * @package Notadd\Payment
 */
class PaymentServiceProvider extends ServiceProvider {
    use InjectConfigTrait, InjectRouterTrait, InjectSettingTrait;
    /**
     * @var bool
     */
    protected $defer = false;
    /**
     * @return void
     */
    public function boot() {
        $this->getConfig()->set('pay', [
            'default' => 'alipay',
            'gateways' => [
                'alipay' => [
                    'driver'  => 'Alipay_Express',
                    'options' => [
                        'partner' => $this->getSetting()->get('payment.alipay.mch.id'),
                        'key' => $this->getSetting()->get('payment.alipay.mch.secret'),
                        'sellerEmail' => $this->getSetting()->get('payment.alipay.partner.email'),
                        'returnUrl' => $this->getSetting()->get('payment.alipay.return.url'),
                        'notifyUrl' => $this->getSetting()->get('payment.alipay.notify.url'),
                    ]
                ],
                'wechatpay' => [
                    'driver' => 'Wechatpay_Native',
                    'options' => [
                        'appid' => $this->getSetting()->get('payment.wechat.app.id'),
                        'api_key' => $this->getSetting()->get('payment.wechat.app.secret'),
                        'mch_id' => $this->getSetting()->get('payment.wechat.mch.id'),
                        'mch_key' => $this->getSetting()->get('payment.wechat.mch.secret'),
                        'notify_url' => $this->getSetting()->get('payment.wechat.notify.url')
                    ]
                ]
            ]
        ]);
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function() {
            $this->getRouter()->resource('payment', AdminPaymentController::class);
        });
        $this->getRouter()->any('notify/{type}', NotifyController::class . '@handle');
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