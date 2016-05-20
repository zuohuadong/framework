<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:05
 */
namespace Notadd\Payment;
use Notadd\Payment\Factories\Gateway as GatewayFactory;
/**
 * Class Payment
 * @package Notadd\Payment
 */
class Payment {
    /**
     * @var
     */
    private static $factory;
    /**
     * @return \Notadd\Payment\Factories\Gateway
     */
    public static function getFactory() {
        if(is_null(static::$factory)) {
            static::$factory = new GatewayFactory;
        }
        return static::$factory;
    }
    /**
     * @param \Notadd\Payment\Factories\Gateway|null $factory
     */
    public static function setFactory(GatewayFactory $factory = null) {
        static::$factory = $factory;
    }
    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters) {
        $factory = static::getFactory();
        return call_user_func_array([
            $factory,
            $method
        ], $parameters);
    }
}