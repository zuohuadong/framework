<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:41
 */
namespace Notadd\Payment\Commons;
/**
 * Class Issuer
 * @package Notadd\Payment\Commons
 */
class Issuer {
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var null|string
     */
    protected $paymentMethod;
    /**
     * Issuer constructor.
     * @param $id
     * @param $name
     * @param null $paymentMethod
     */
    public function __construct($id, $name, $paymentMethod = null) {
        $this->id = $id;
        $this->name = $name;
        $this->paymentMethod = $paymentMethod;
    }
    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    /**
     * @return null|string
     */
    public function getPaymentMethod() {
        return $this->paymentMethod;
    }
}