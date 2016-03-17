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
use Notadd\Payment\Factories\Gateway;
/**
 * Class PaymentServiceProvider
 * @package Notadd\Payment
 */
class PaymentServiceProvider extends ServiceProvider {
    use InjectConfigTrait;
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
                        'solutionType'   => '',
                        'landingPage'    => '',
                        'headerImageUrl' => ''
                    ]
                ]
            ]
        ]);
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