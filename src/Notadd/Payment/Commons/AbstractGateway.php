<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 18:14
 */
namespace Notadd\Payment\Commons;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use Notadd\Payment\Contracts\Gateway as GatewayContract;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
/**
 * Class AbstractGateway
 * @package Notadd\Payment\Commons
 */
abstract class AbstractGateway implements GatewayContract {
    /**
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;
    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $httpRequest;
    /**
     * AbstractGateway constructor.
     * @param \GuzzleHttp\ClientInterface|null $httpClient
     * @param \Symfony\Component\HttpFoundation\Request|null $httpRequest
     */
    public function __construct(ClientInterface $httpClient = null, HttpRequest $httpRequest = null) {
        $this->httpClient = $httpClient ?: $this->getDefaultHttpClient();
        $this->httpRequest = $httpRequest ?: $this->getDefaultHttpRequest();
        $this->initialize();
    }
    /**
     * @return string
     */
    public function getShortName() {
        return Helper::getGatewayShortName(get_class($this));
    }
    /**
     * @param array $parameters
     * @return $this
     */
    public function initialize(array $parameters = []) {
        $this->parameters = new ParameterBag;
        foreach($this->getDefaultParameters() as $key => $value) {
            if(is_array($value)) {
                $this->parameters->set($key, reset($value));
            } else {
                $this->parameters->set($key, $value);
            }
        }
        Helper::initialize($this, $parameters);
        return $this;
    }
    /**
     * @return array
     */
    public function getDefaultParameters() {
        return [];
    }
    /**
     * @return array
     */
    public function getParameters() {
        return $this->parameters->all();
    }
    /**
     * @param  string $key
     * @return mixed
     */
    public function getParameter($key) {
        return $this->parameters->get($key);
    }
    /**
     * @param  string $key
     * @param  mixed $value
     * @return $this
     */
    public function setParameter($key, $value) {
        $this->parameters->set($key, $value);
        return $this;
    }
    /**
     * @return boolean
     */
    public function getTestMode() {
        return $this->getParameter('testMode');
    }
    /**
     * @param  boolean $value
     * @return $this
     */
    public function setTestMode($value) {
        return $this->setParameter('testMode', $value);
    }
    /**
     * @return string
     */
    public function getCurrency() {
        return strtoupper($this->getParameter('currency'));
    }
    /**
     * @param  string $value
     * @return $this
     */
    public function setCurrency($value) {
        return $this->setParameter('currency', $value);
    }
    /**
     * @return boolean
     */
    public function supportsAuthorize() {
        return method_exists($this, 'authorize');
    }
    /**
     * @return boolean
     */
    public function supportsCompleteAuthorize() {
        return method_exists($this, 'completeAuthorize');
    }
    /**
     * @return boolean
     */
    public function supportsCapture() {
        return method_exists($this, 'capture');
    }
    /**
     * @return boolean
     */
    public function supportsPurchase() {
        return method_exists($this, 'purchase');
    }
    /**
     * @return boolean
     */
    public function supportsCompletePurchase() {
        return method_exists($this, 'completePurchase');
    }
    /**
     * @return bool
     */
    public function supportsRefund() {
        return method_exists($this, 'refund');
    }
    /**
     * @return bool
     */
    public function supportsVoid() {
        return method_exists($this, 'void');
    }
    /**
     * @return bool
     */
    public function supportsAcceptNotification() {
        return method_exists($this, 'acceptNotification');
    }
    /**
     * @return bool
     */
    public function supportsCreateCard() {
        return method_exists($this, 'createCard');
    }
    /**
     * @return bool
     */
    public function supportsDeleteCard() {
        return method_exists($this, 'deleteCard');
    }
    /**
     * @return bool
     */
    public function supportsUpdateCard() {
        return method_exists($this, 'updateCard');
    }
    /**
     * @param $class
     * @param array $parameters
     * @return mixed
     */
    protected function createRequest($class, array $parameters) {
        $obj = new $class($this->httpClient, $this->httpRequest);
        return $obj->initialize(array_replace($this->getParameters(), $parameters));
    }
    /**
     * @return \GuzzleHttp\Client
     */
    protected function getDefaultHttpClient() {
        return new HttpClient([
                'curl.options' => [CURLOPT_CONNECTTIMEOUT => 60],
            ]);
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getDefaultHttpRequest() {
        return HttpRequest::createFromGlobals();
    }
}