<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-19 21:00
 */
namespace Notadd\Payment\Controllers;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Notadd\Foundation\Routing\Controller;
use Notadd\Foundation\SearchEngine\Optimization;
use Notadd\Payment\Models\Payment;
use Notadd\Setting\Factory as SettingFactory;
class NotifyController extends Controller {
    /**
     * @var \Notadd\Payment\PaymentManager
     */
    protected $payment;
    /**
     * NotifyController constructor.
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Events\Dispatcher $events
     * @param \Illuminate\Contracts\Logging\Log $log
     * @param \Illuminate\Routing\Redirector $redirect
     * @param \Notadd\Setting\Factory $setting
     * @param \Notadd\Foundation\SearchEngine\Optimization $seo
     * @param \Illuminate\Contracts\View\Factory $view
     */
    public function __construct(Application $app, Dispatcher $events, Log $log, Redirector $redirect, SettingFactory $setting, Optimization $seo, ViewFactory $view) {
        parent::__construct($app, $events, $log, $redirect, $setting, $seo, $view);
        $this->payment = $app->make('pay');
    }
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
                    $payment->save($data->toArray());
                } else {
                    $data->put('is_success', false);
                }
                return $this->redirect->to('');
                break;
            case 'wechatpay':
                $this->log->error('微信回调开始：');
                $gateway = $this->payment->gateway('wechatpay');
                $response = $gateway->completePurchase([
                    'request_params' => file_get_contents('php://input')
                ]);
                $tmp = $response->getData();
                $payment = Payment::whereTradeNumber($tmp['out_trade_no'])->whereType('wechatpay')->first();
                $data = new Collection();
                $data->put('subject', $tmp['attach']);
                $data->put('data', json_encode($tmp));
                $response->send();
                if ($response->isPaid()) {
                    $data->put('is_success', true);
                    $payment->save($data->toArray());
                    return true;
                }else{
                    return false;
                }
                break;
        }
        return false;
    }
}