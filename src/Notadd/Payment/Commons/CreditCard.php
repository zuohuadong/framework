<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:28
 */
namespace Notadd\Payment\Commons;
use Notadd\Payment\Exceptions\InvalidCreditCardException;
use Symfony\Component\HttpFoundation\ParameterBag;
/**
 * Class CreditCard
 * @package Notadd\Payment\Commons
 */
class CreditCard {
    const BRAND_VISA = 'visa';
    const BRAND_MASTERCARD = 'mastercard';
    const BRAND_DISCOVER = 'discover';
    const BRAND_AMEX = 'amex';
    const BRAND_DINERS_CLUB = 'diners_club';
    const BRAND_JCB = 'jcb';
    const BRAND_SWITCH = 'switch';
    const BRAND_SOLO = 'solo';
    const BRAND_DANKORT = 'dankort';
    const BRAND_MAESTRO = 'maestro';
    const BRAND_FORBRUGSFORENINGEN = 'forbrugsforeningen';
    const BRAND_LASER = 'laser';
    /**
     * @var array
     */
    protected $supported_cards = array(
        self::BRAND_VISA => '/^4\d{12}(\d{3})?$/',
        self::BRAND_MASTERCARD => '/^(5[1-5]\d{4}|677189)\d{10}$/',
        self::BRAND_DISCOVER => '/^(6011|65\d{2}|64[4-9]\d)\d{12}|(62\d{14})$/',
        self::BRAND_AMEX => '/^3[47]\d{13}$/',
        self::BRAND_DINERS_CLUB => '/^3(0[0-5]|[68]\d)\d{11}$/',
        self::BRAND_JCB => '/^35(28|29|[3-8]\d)\d{12}$/',
        self::BRAND_SWITCH => '/^6759\d{12}(\d{2,3})?$/',
        self::BRAND_SOLO => '/^6767\d{12}(\d{2,3})?$/',
        self::BRAND_DANKORT => '/^5019\d{12}$/',
        self::BRAND_MAESTRO => '/^(5[06-8]|6\d)\d{10,17}$/',
        self::BRAND_FORBRUGSFORENINGEN => '/^600722\d{10}$/',
        self::BRAND_LASER => '/^(6304|6706|6709|6771(?!89))\d{8}(\d{4}|\d{6,7})?$/',
    );
    /**
     * @var
     */
    protected $parameters;
    /**
     * CreditCard constructor.
     * @param null $parameters
     */
    public function __construct($parameters = null) {
        $this->initialize($parameters);
    }
    /**
     * @return array
     */
    public function getSupportedBrands() {
        return $this->supported_cards;
    }
    /**
     * @param $name
     * @param $expression
     * @return bool
     */
    public function addSupportedBrand($name, $expression) {
        $known_brands = array_keys($this->supported_cards);
        if(in_array($name, $known_brands)) {
            return false;
        }
        $this->supported_cards[$name] = $expression;
        return true;
    }
    /**
     * @param null $parameters
     * @return $this
     */
    public function initialize($parameters = null) {
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
        $this->parameters->set($key, $value);
        return $this;
    }
    /**
     * @param $key
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    protected function setYearParameter($key, $value) {
        if(null === $value || '' === $value) {
            $value = null;
        } else {
            $value = (int)gmdate('Y', gmmktime(0, 0, 0, 1, 1, (int)$value));
        }
        return $this->setParameter($key, $value);
    }
    /**
     * @throws \Notadd\Payment\Exceptions\InvalidCreditCardException
     */
    public function validate() {
        foreach(array(
                    'number',
                    'expiryMonth',
                    'expiryYear'
                ) as $key) {
            if(!$this->getParameter($key)) {
                throw new InvalidCreditCardException("The $key parameter is required");
            }
        }
        if($this->getExpiryDate('Ym') < gmdate('Ym')) {
            throw new InvalidCreditCardException('Card has expired');
        }
        if(!Helper::validateLuhn($this->getNumber())) {
            throw new InvalidCreditCardException('Card number is invalid');
        }
        if(!is_null($this->getNumber()) && !preg_match('/^\d{12,19}$/i', $this->getNumber())) {
            throw new InvalidCreditCardException('Card number should have 12 to 19 digits');
        }
    }
    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->getBillingTitle();
    }
    /**
     * @param $value
     * @return $this
     */
    public function setTitle($value) {
        $this->setBillingTitle($value);
        $this->setShippingTitle($value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getFirstName() {
        return $this->getBillingFirstName();
    }
    /**
     * @param $value
     * @return $this
     */
    public function setFirstName($value) {
        $this->setBillingFirstName($value);
        $this->setShippingFirstName($value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getLastName() {
        return $this->getBillingLastName();
    }
    /**
     * @param $value
     * @return $this
     */
    public function setLastName($value) {
        $this->setBillingLastName($value);
        $this->setShippingLastName($value);
        return $this;
    }
    /**
     * @return string
     */
    public function getName() {
        return $this->getBillingName();
    }
    /**
     * @param $value
     * @return $this
     */
    public function setName($value) {
        $this->setBillingName($value);
        $this->setShippingName($value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getNumber() {
        return $this->getParameter('number');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setNumber($value) {
        return $this->setParameter('number', preg_replace('/\D/', '', $value));
    }
    /**
     * @return null
     */
    public function getNumberLastFour() {
        return substr($this->getNumber(), -4, 4) ?: null;
    }
    /**
     * @param string $mask
     * @return string
     */
    public function getNumberMasked($mask = 'X') {
        $maskLength = strlen($this->getNumber()) - 4;
        return str_repeat($mask, $maskLength) . $this->getNumberLastFour();
    }
    /**
     * @return int|string
     */
    public function getBrand() {
        foreach($this->getSupportedBrands() as $brand => $val) {
            if(preg_match($val, $this->getNumber())) {
                return $brand;
            }
        }
    }
    /**
     * @return mixed
     */
    public function getExpiryMonth() {
        return $this->getParameter('expiryMonth');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setExpiryMonth($value) {
        return $this->setParameter('expiryMonth', (int)$value);
    }
    /**
     * @return mixed
     */
    public function getExpiryYear() {
        return $this->getParameter('expiryYear');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setExpiryYear($value) {
        return $this->setYearParameter('expiryYear', $value);
    }
    /**
     * @param $format
     * @return string
     */
    public function getExpiryDate($format) {
        return gmdate($format, gmmktime(0, 0, 0, $this->getExpiryMonth(), 1, $this->getExpiryYear()));
    }
    /**
     * @return mixed
     */
    public function getStartMonth() {
        return $this->getParameter('startMonth');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setStartMonth($value) {
        return $this->setParameter('startMonth', (int)$value);
    }
    /**
     * @return mixed
     */
    public function getStartYear() {
        return $this->getParameter('startYear');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setStartYear($value) {
        return $this->setYearParameter('startYear', $value);
    }
    /**
     * @param $format
     * @return string
     */
    public function getStartDate($format) {
        return gmdate($format, gmmktime(0, 0, 0, $this->getStartMonth(), 1, $this->getStartYear()));
    }
    /**
     * @return mixed
     */
    public function getCvv() {
        return $this->getParameter('cvv');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setCvv($value) {
        return $this->setParameter('cvv', $value);
    }
    /**
     * @return mixed
     */
    public function getTracks() {
        return $this->getParameter('tracks');
    }
    /**
     * @return null
     */
    public function getTrack1() {
        $track1 = null;
        if($tracks = $this->getTracks()) {
            $pattern = '/\%B\d{1,19}\^.{2,26}\^\d{4}\d*\?/';
            if(preg_match($pattern, $tracks, $matches) === 1) {
                $track1 = $matches[0];
            }
        }
        return $track1;
    }
    /**
     * @return null
     */
    public function getTrack2() {
        $track2 = null;
        if($tracks = $this->getTracks()) {
            $pattern = '/;\d{1,19}=\d{4}\d*\?/';
            if(preg_match($pattern, $tracks, $matches) === 1) {
                $track2 = $matches[0];
            }
        }
        return $track2;
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setTracks($value) {
        return $this->setParameter('tracks', $value);
    }
    /**
     * @return mixed
     */
    public function getIssueNumber() {
        return $this->getParameter('issueNumber');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setIssueNumber($value) {
        return $this->setParameter('issueNumber', $value);
    }
    /**
     * @return mixed
     */
    public function getBillingTitle() {
        return $this->getParameter('billingTitle');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingTitle($value) {
        return $this->setParameter('billingTitle', $value);
    }
    /**
     * @return string
     */
    public function getBillingName() {
        return trim($this->getBillingFirstName() . ' ' . $this->getBillingLastName());
    }
    /**
     * @param $value
     * @return $this
     */
    public function setBillingName($value) {
        $names = explode(' ', $value, 2);
        $this->setBillingFirstName($names[0]);
        $this->setBillingLastName(isset($names[1]) ? $names[1] : null);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getBillingFirstName() {
        return $this->getParameter('billingFirstName');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingFirstName($value) {
        return $this->setParameter('billingFirstName', $value);
    }
    /**
     * @return mixed
     */
    public function getBillingLastName() {
        return $this->getParameter('billingLastName');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingLastName($value) {
        return $this->setParameter('billingLastName', $value);
    }
    /**
     * @return mixed
     */
    public function getBillingCompany() {
        return $this->getParameter('billingCompany');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingCompany($value) {
        return $this->setParameter('billingCompany', $value);
    }
    /**
     * @return mixed
     */
    public function getBillingAddress1() {
        return $this->getParameter('billingAddress1');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingAddress1($value) {
        return $this->setParameter('billingAddress1', $value);
    }
    /**
     * @return mixed
     */
    public function getBillingAddress2() {
        return $this->getParameter('billingAddress2');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingAddress2($value) {
        return $this->setParameter('billingAddress2', $value);
    }
    /**
     * @return mixed
     */
    public function getBillingCity() {
        return $this->getParameter('billingCity');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingCity($value) {
        return $this->setParameter('billingCity', $value);
    }
    /**
     * @return mixed
     */
    public function getBillingPostcode() {
        return $this->getParameter('billingPostcode');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingPostcode($value) {
        return $this->setParameter('billingPostcode', $value);
    }
    /**
     * @return mixed
     */
    public function getBillingState() {
        return $this->getParameter('billingState');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingState($value) {
        return $this->setParameter('billingState', $value);
    }
    /**
     * @return mixed
     */
    public function getBillingCountry() {
        return $this->getParameter('billingCountry');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingCountry($value) {
        return $this->setParameter('billingCountry', $value);
    }
    /**
     * @return mixed
     */
    public function getBillingPhone() {
        return $this->getParameter('billingPhone');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingPhone($value) {
        return $this->setParameter('billingPhone', $value);
    }
    /**
     * @return mixed
     */
    public function getBillingPhoneExtension() {
        return $this->getParameter('billingPhoneExtension');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingPhoneExtension($value) {
        return $this->setParameter('billingPhoneExtension', $value);
    }
    /**
     * @return mixed
     */
    public function getBillingFax() {
        return $this->getParameter('billingFax');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBillingFax($value) {
        return $this->setParameter('billingFax', $value);
    }
    /**
     * @return mixed
     */
    public function getShippingTitle() {
        return $this->getParameter('shippingTitle');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingTitle($value) {
        return $this->setParameter('shippingTitle', $value);
    }
    /**
     * @return string
     */
    public function getShippingName() {
        return trim($this->getShippingFirstName() . ' ' . $this->getShippingLastName());
    }
    /**
     * @param $value
     * @return $this
     */
    public function setShippingName($value) {
        $names = explode(' ', $value, 2);
        $this->setShippingFirstName($names[0]);
        $this->setShippingLastName(isset($names[1]) ? $names[1] : null);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getShippingFirstName() {
        return $this->getParameter('shippingFirstName');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingFirstName($value) {
        return $this->setParameter('shippingFirstName', $value);
    }
    /**
     * @return mixed
     */
    public function getShippingLastName() {
        return $this->getParameter('shippingLastName');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingLastName($value) {
        return $this->setParameter('shippingLastName', $value);
    }
    /**
     * @return mixed
     */
    public function getShippingCompany() {
        return $this->getParameter('shippingCompany');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingCompany($value) {
        return $this->setParameter('shippingCompany', $value);
    }
    /**
     * @return mixed
     */
    public function getShippingAddress1() {
        return $this->getParameter('shippingAddress1');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingAddress1($value) {
        return $this->setParameter('shippingAddress1', $value);
    }
    /**
     * @return mixed
     */
    public function getShippingAddress2() {
        return $this->getParameter('shippingAddress2');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingAddress2($value) {
        return $this->setParameter('shippingAddress2', $value);
    }
    /**
     * @return mixed
     */
    public function getShippingCity() {
        return $this->getParameter('shippingCity');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingCity($value) {
        return $this->setParameter('shippingCity', $value);
    }
    /**
     * @return mixed
     */
    public function getShippingPostcode() {
        return $this->getParameter('shippingPostcode');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingPostcode($value) {
        return $this->setParameter('shippingPostcode', $value);
    }
    /**
     * @return mixed
     */
    public function getShippingState() {
        return $this->getParameter('shippingState');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingState($value) {
        return $this->setParameter('shippingState', $value);
    }
    /**
     * @return mixed
     */
    public function getShippingCountry() {
        return $this->getParameter('shippingCountry');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingCountry($value) {
        return $this->setParameter('shippingCountry', $value);
    }
    /**
     * @return mixed
     */
    public function getShippingPhone() {
        return $this->getParameter('shippingPhone');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingPhone($value) {
        return $this->setParameter('shippingPhone', $value);
    }
    /**
     * @return mixed
     */
    public function getShippingPhoneExtension() {
        return $this->getParameter('shippingPhoneExtension');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingPhoneExtension($value) {
        return $this->setParameter('shippingPhoneExtension', $value);
    }
    /**
     * @return mixed
     */
    public function getShippingFax() {
        return $this->getParameter('shippingFax');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setShippingFax($value) {
        return $this->setParameter('shippingFax', $value);
    }
    /**
     * @return mixed
     */
    public function getAddress1() {
        return $this->getParameter('billingAddress1');
    }
    /**
     * @param $value
     * @return $this
     */
    public function setAddress1($value) {
        $this->setParameter('billingAddress1', $value);
        $this->setParameter('shippingAddress1', $value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getAddress2() {
        return $this->getParameter('billingAddress2');
    }
    /**
     * @param $value
     * @return $this
     */
    public function setAddress2($value) {
        $this->setParameter('billingAddress2', $value);
        $this->setParameter('shippingAddress2', $value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getCity() {
        return $this->getParameter('billingCity');
    }
    /**
     * @param $value
     * @return $this
     */
    public function setCity($value) {
        $this->setParameter('billingCity', $value);
        $this->setParameter('shippingCity', $value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getPostcode() {
        return $this->getParameter('billingPostcode');
    }
    /**
     * @param $value
     * @return $this
     */
    public function setPostcode($value) {
        $this->setParameter('billingPostcode', $value);
        $this->setParameter('shippingPostcode', $value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getState() {
        return $this->getParameter('billingState');
    }
    /**
     * @param $value
     * @return $this
     */
    public function setState($value) {
        $this->setParameter('billingState', $value);
        $this->setParameter('shippingState', $value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getCountry() {
        return $this->getParameter('billingCountry');
    }
    /**
     * @param $value
     * @return $this
     */
    public function setCountry($value) {
        $this->setParameter('billingCountry', $value);
        $this->setParameter('shippingCountry', $value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getPhone() {
        return $this->getParameter('billingPhone');
    }
    /**
     * @param $value
     * @return $this
     */
    public function setPhone($value) {
        $this->setParameter('billingPhone', $value);
        $this->setParameter('shippingPhone', $value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getPhoneExtension() {
        return $this->getParameter('billingPhoneExtension');
    }
    /**
     * @param $value
     * @return $this
     */
    public function setPhoneExtension($value) {
        $this->setParameter('billingPhoneExtension', $value);
        $this->setParameter('shippingPhoneExtension', $value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getFax() {
        return $this->getParameter('billingFax');
    }
    /**
     * @param $value
     * @return $this
     */
    public function setFax($value) {
        $this->setParameter('billingFax', $value);
        $this->setParameter('shippingFax', $value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getCompany() {
        return $this->getParameter('billingCompany');
    }
    /**
     * @param $value
     * @return $this
     */
    public function setCompany($value) {
        $this->setParameter('billingCompany', $value);
        $this->setParameter('shippingCompany', $value);
        return $this;
    }
    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->getParameter('email');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setEmail($value) {
        return $this->setParameter('email', $value);
    }
    /**
     * @param string $format
     * @return null
     */
    public function getBirthday($format = 'Y-m-d') {
        $value = $this->getParameter('birthday');
        return $value ? $value->format($format) : null;
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setBirthday($value) {
        if($value) {
            $value = new DateTime($value, new DateTimeZone('UTC'));
        } else {
            $value = null;
        }
        return $this->setParameter('birthday', $value);
    }
    /**
     * @return mixed
     */
    public function getGender() {
        return $this->getParameter('gender');
    }
    /**
     * @param $value
     * @return \Notadd\Payment\Commons\CreditCard
     */
    public function setGender($value) {
        return $this->setParameter('gender', $value);
    }
}