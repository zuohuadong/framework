<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:39
 */
namespace Notadd\Payment\Factories;
use GuzzleHttp\ClientInterface;
use Notadd\Payment\Commons\Helper;
use Notadd\Payment\Exceptions\RuntimeException;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
/**
 * Class Gateway
 * @package Notadd\Payment\Factories
 */
class Gateway {
    /**
     * @var array
     */
    private $gateways = [];
    /**
     * @return array
     */
    public function all() {
        return $this->gateways;
    }
    /**
     * @param array $gateways
     */
    public function replace(array $gateways) {
        $this->gateways = $gateways;
    }
    /**
     * @param $className
     */
    public function register($className) {
        if(!in_array($className, $this->gateways)) {
            $this->gateways[] = $className;
        }
    }
    /**
     * @return array
     */
    public function find() {
        foreach($this->getSupportedGateways() as $gateway) {
            $class = Helper::getGatewayClassName($gateway);
            if(class_exists($class)) {
                $this->register($gateway);
            }
        }
        ksort($this->gateways);
        return $this->all();
    }
    /**
     * @param $class
     * @param \GuzzleHttp\ClientInterface|null $httpClient
     * @param \Symfony\Component\HttpFoundation\Request|null $httpRequest
     * @return mixed
     */
    public function create($class, ClientInterface $httpClient = null, HttpRequest $httpRequest = null) {
        $class = Helper::getGatewayClassName($class);
        if(!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }
        return new $class($httpClient, $httpRequest);
    }
    /**
     * @return mixed
     */
    public function getSupportedGateways() {
        $package = json_decode(file_get_contents(__DIR__ . '/../../../composer.json'), true);
        return $package['extra']['gateways'];
    }
}