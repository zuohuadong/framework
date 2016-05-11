<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-19 21:00
 */
namespace Notadd\Payment\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Notadd\Foundation\Routing\Controller;
use Notadd\Payment\Models\Payment;
/**
 * Class NotifyController
 * @package Notadd\Payment\Controllers
 */
class NotifyController extends Controller {
    /**
     * @var \Notadd\Payment\PaymentManager
     */
    protected $payment;
    /**
     * NotifyController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->payment = $this->app->make('pay');
    }
    /**
     * @param $type
     * @param \Illuminate\Http\Request $request
     * @return bool|\Illuminate\Http\RedirectResponse|mixed
     */
    public function handle($type, Request $request) {
        if(!in_array($type, ['alipay', 'wechatpay'])) {
            return false;
        }
        switch($type) {
            case 'alipay':
                $gateway = $this->payment->gateway('alipay');
                $options = [
                    'request_params'=> $request->all(),
                ];
                $payment = Payment::whereTradeNumber($request->input('out_trade_no'))->whereType('alipay')->first();
                $data = new Collection();
                $data->put('subject', $request->input('subject'));
                $data->put('data', json_encode($request->toArray()));
                $response = $gateway->completePurchase($options)->send();
                if($response->isPaid()) {
                    $data->put('is_success', true);
                } else {
                    $data->put('is_success', false);
                }
                $payment->update($data->toArray());
                return $this->redirect->to('');
                break;
            case 'wechatpay':
                $this->log->error('微信回调开始：');
                $gateway = $this->payment->gateway('wechatpay');
                $this->log->error('微信回调请求信息：' . file_get_contents('php://input'));
                $response = $gateway->completePurchase([
                    'request_params' => file_get_contents('php://input')
                ])->send();
                $tmp = $response->getRequestData();
                $this->log->error('微信回调请求Response数据：' . print_r($response->getRequestData(), true));
                $payment = Payment::whereTradeNumber($tmp['out_trade_no'])->whereType('wechatpay')->first();
                $data = new Collection();
                $data->put('subject', $tmp['attach']);
                $data->put('data', json_encode($tmp));
                if ($response->isPaid()) {
                    $data->put('is_success', true);
                }else{
                    $data->put('is_success', false);
                }
                $payment->update($data->toArray());
                return $data->get('is_success');
                break;
        }
        return false;
    }
}