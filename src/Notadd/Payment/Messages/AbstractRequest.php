<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 17:02
 */
namespace Notadd\Payment\Messages;
use GuzzleHttp\ClientInterface;
use InvalidArgumentException;
use Notadd\Payment\Commons\CreditCard;
use Notadd\Payment\Commons\Currency;
use Notadd\Payment\Commons\Helper;
use Notadd\Payment\Commons\ItemBag;
use Notadd\Payment\Contracts\Request as RequestContract;
use Notadd\Payment\Exceptions\InvalidRequestException;
use Notadd\Payment\Exceptions\RuntimeException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
/**
 * Class AbstractRequest
 * @package Notadd\Payment\Messages
 */
abstract class AbstractRequest implements RequestContract {
    /**
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $httpRequest;
    /**
     * @var \Notadd\Payment\Contracts\Request
     */
    protected $response;
    /**
     * @var bool
     */
    protected $zeroAmountAllowed = true;
    /**
     * @var bool
     */
    protected $negativeAmountAllowed = false;
    /**
     * AbstractRequest constructor.
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     */
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest) {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;
        $this->initialize();
    }
    /**
     * @param array $parameters
     * @return $this
     */
    public function initialize(array $parameters = array()) {
        if(null !== $this->response) {
            throw new RuntimeException('Request cannot be modified after it has been sent!');
        }
        $this->parameters = new ParameterBag;
        Helper::initialize($this, $parameters);
        return $this;
    }
    /**
     * @return array
     */
    public function getParameters() {
        return $this->parameters->all();
    }
    /**
     * @param $key
     * @return mixed
     */
    protected function getParameter($key) {
        return $this->parameters->get($key);
    }
    /**
     * @param $key
     * @param $value
     * @return $this
     */
    protected function setParameter($key, $value) {
        if(null !== $this->response) {
            throw new RuntimeException('Request cannot be modified after it has been sent!');
        }
        $this->parameters->set($key, $value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getTestMode() {
        return $this->getParameter('testMode');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setTestMode($value) {
        return $this->setParameter('testMode', $value);
    }
    /**
     * @throws \Notadd\Payment\Exceptions\InvalidRequestException
     */
    public function validate() {
        foreach(func_get_args() as $key) {
            $value = $this->parameters->get($key);
            if(!isset($value)) {
                throw new InvalidRequestException("The $key parameter is required");
            }
        }
    }
    /**
     * Get the card.
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function getCard() {
        return $this->getParameter('card');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setCard($value) {
        if($value && !$value instanceof CreditCard) {
            $value = new CreditCard($value);
        }
        return $this->setParameter('card', $value);
    }
    /**
     * @return mixed
     */
    public function getToken() {
        return $this->getParameter('token');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setToken($value) {
        return $this->setParameter('token', $value);
    }
    /**
     * @return mixed
     */
    public function getCardReference() {
        return $this->getParameter('cardReference');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setCardReference($value) {
        return $this->setParameter('cardReference', $value);
    }
    /**
     * @param $value
     * @return float
     * @throws \Notadd\Payment\Exceptions\InvalidRequestException
     */
    public function toFloat($value) {
        try {
            return Helper::toFloat($value);
        } catch(InvalidArgumentException $e) {
            throw new InvalidRequestException($e->getMessage(), $e->getCode(), $e);
        }
    }
    /**
     * @return string
     * @throws \Notadd\Payment\Exceptions\InvalidRequestException
     */
    public function getAmount() {
        $amount = $this->getParameter('amount');
        if($amount !== null) {
            if($this->getCurrencyDecimalPlaces() > 0) {
                if(is_int($amount) || (is_string($amount) && false === strpos((string)$amount, '.'))) {
                    throw new InvalidRequestException('Please specify amount as a string or float, ' . 'with decimal places (e.g. \'10.00\' to represent $10.00).');
                };
            }
            $amount = $this->toFloat($amount);
            if(!$this->negativeAmountAllowed && $amount < 0) {
                throw new InvalidRequestException('A negative amount is not allowed.');
            }
            if(!$this->zeroAmountAllowed && $amount === 0.0) {
                throw new InvalidRequestException('A zero amount is not allowed.');
            }
            $decimal_count = strlen(substr(strrchr(sprintf('%.8g', $amount), '.'), 1));
            if($decimal_count > $this->getCurrencyDecimalPlaces()) {
                throw new InvalidRequestException('Amount precision is too high for currency.');
            }
            return $this->formatCurrency($amount);
        }
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setAmount($value) {
        return $this->setParameter('amount', $value);
    }
    /**
     * @return int
     * @throws \Notadd\Payment\Exceptions\InvalidRequestException
     */
    public function getAmountInteger() {
        return (int)round($this->getAmount() * $this->getCurrencyDecimalFactor());
    }
    /**
     * @return mixed
     */
    public function getCurrency() {
        return $this->getParameter('currency');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setCurrency($value) {
        return $this->setParameter('currency', strtoupper($value));
    }
    /**
     * @return mixed
     */
    public function getCurrencyNumeric() {
        if($currency = Currency::find($this->getCurrency())) {
            return $currency->getNumeric();
        }
    }
    /**
     * @return int|mixed
     */
    public function getCurrencyDecimalPlaces() {
        if($currency = Currency::find($this->getCurrency())) {
            return $currency->getDecimals();
        }
        return 2;
    }
    /**
     * @return number
     */
    private function getCurrencyDecimalFactor() {
        return pow(10, $this->getCurrencyDecimalPlaces());
    }
    /**
     * @param $amount
     * @return string
     */
    public function formatCurrency($amount) {
        return number_format($amount, $this->getCurrencyDecimalPlaces(), '.', '');
    }
    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->getParameter('description');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setDescription($value) {
        return $this->setParameter('description', $value);
    }
    /**
     * @return mixed
     */
    public function getTransactionId() {
        return $this->getParameter('transactionId');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setTransactionId($value) {
        return $this->setParameter('transactionId', $value);
    }
    /**
     * @return mixed
     */
    public function getTransactionReference() {
        return $this->getParameter('transactionReference');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setTransactionReference($value) {
        return $this->setParameter('transactionReference', $value);
    }
    /**
     * @return mixed
     */
    public function getItems() {
        return $this->getParameter('items');
    }
    /**
     * @param $items
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setItems($items) {
        if($items && !$items instanceof ItemBag) {
            $items = new ItemBag($items);
        }
        return $this->setParameter('items', $items);
    }
    /**
     * @return mixed
     */
    public function getClientIp() {
        return $this->getParameter('clientIp');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setClientIp($value) {
        return $this->setParameter('clientIp', $value);
    }
    /**
     * @return mixed
     */
    public function getReturnUrl() {
        return $this->getParameter('returnUrl');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setReturnUrl($value) {
        return $this->setParameter('returnUrl', $value);
    }
    /**
     * @return mixed
     */
    public function getCancelUrl() {
        return $this->getParameter('cancelUrl');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setCancelUrl($value) {
        return $this->setParameter('cancelUrl', $value);
    }
    /**
     * @return mixed
     */
    public function getNotifyUrl() {
        return $this->getParameter('notifyUrl');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setNotifyUrl($value) {
        return $this->setParameter('notifyUrl', $value);
    }
    /**
     * @return mixed
     */
    public function getIssuer() {
        return $this->getParameter('issuer');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setIssuer($value) {
        return $this->setParameter('issuer', $value);
    }
    /**
     * @return mixed
     */
    public function getPaymentMethod() {
        return $this->getParameter('paymentMethod');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Messages\AbstractRequest
     */
    public function setPaymentMethod($value) {
        return $this->setParameter('paymentMethod', $value);
    }
    /**
     * @return \Notadd\Payment\Contracts\Response
     */
    public function send() {
        $data = $this->getData();
        return $this->sendData($data);
    }
    /**
     * @return \Notadd\Payment\Contracts\Request
     */
    public function getResponse() {
        if(null === $this->response) {
            throw new RuntimeException('You must call send() before accessing the Response!');
        }
        return $this->response;
    }
}