<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 15:54
 */
namespace Notadd\Payment;
use Illuminate\Support\ServiceProvider;
use Notadd\Payment\Factories\Gateway;
/**
 * Class PaymentServiceProvider
 * @package Notadd\Payment
 */
class PaymentServiceProvider extends ServiceProvider {
    /**
     * @var bool
     */
    protected $defer = false;
    /**
     * @return void
     */
    public function boot() {
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