<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 18:07
 */
namespace Notadd\Payment;
use BadMethodCallException;
use Illuminate\Support\Str;
use Notadd\Payment\Commons\CreditCard;
use Notadd\Payment\Commons\Helper;
use UnexpectedValueException;
/**
 * Class PaymentManage
 * @package Notadd\Payment
 */
class PaymentManager {
    /**
     * @var \Notadd\Foundation\Application
     */
    protected $app;
    /**
     * @var \Notadd\Payment\Factories\Gateway
     */
    protected $factory;
    /**
     * @var string
     */
    protected $gateway;
    /**
     * @var array
     */
    protected $gateways = [];
    /**
     * @param  \Notadd\Foundation\Application $app
     * @param $factory
     */
    public function __construct($app, $factory) {
        $this->app = $app;
        $this->factory = $factory;
    }
    /**
     * @param  $name
     * @return \Notadd\Payment\Commons\AbstractGateway
     */
    public function gateway($name = null) {
        $name = $name ?: $this->getGateway();
        if(!isset($this->gateways[$name])) {
            $this->gateways[$name] = $this->resolve($name);
        }
        return $this->gateways[$name];
    }
    /**
     * @param $name
     * @return mixed
     */
    protected function resolve($name) {
        $config = $this->getConfig($name);
        if(is_null($config)) {
            throw new UnexpectedValueException("Gateway [$name] is not defined.");
        }
        $gateway = $this->factory->create($config['driver']);
        $class = trim(Helper::getGatewayClassName($config['driver']), "\\");
        $reflection = new \ReflectionClass($class);
        foreach($config['options'] as $optionName => $value) {
            if(Str::contains($optionName, '_')) {
                $data = explode('_', $optionName);
                foreach($data as $k=>$v) {
                    $data[$k] = ucfirst($v);
                }
                $method = 'set' . implode('', $data);
            } else {
                $method = 'set' . ucfirst($optionName);
            }
            if($reflection->hasMethod($method)) {
                $gateway->{$method}($value);
            }
        }
        return $gateway;
    }
    /**
     * @param $cardInput
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function creditCard($cardInput) {
        return new CreditCard($cardInput);
    }
    /**
     * @return mixed
     */
    protected function getDefault() {
        return $this->app['config']['pay.default'];
    }
    /**
     * @param $name
     * @return mixed
     */
    protected function getConfig($name) {
        return $this->app['config']["pay.gateways.{$name}"];
    }
    /**
     * @return mixed|string
     */
    public function getGateway() {
        if(!isset($this->gateway)) {
            $this->gateway = $this->getDefault();
        }
        return $this->gateway;
    }
    /**
     * @param $name
     */
    public function setGateway($name) {
        $this->gateway = $name;
    }
    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters) {
        $callable = [
            $this->gateway(),
            $method
        ];
        if(method_exists($this->gateway(), $method)) {
            return call_user_func_array($callable, $parameters);
        }
        throw new BadMethodCallException("Method [$method] is not supported by the gateway [$this->gateway].");
    }
}