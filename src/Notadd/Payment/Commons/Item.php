<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:14
 */
namespace Notadd\Payment\Commons;
use Notadd\Payment\Contracts\Item as ItemContract;
use Symfony\Component\HttpFoundation\ParameterBag;
/**
 * Class Item
 * @package Notadd\Payment\Commons
 */
class Item implements ItemContract {
    /**
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;
    /**
     * Item constructor.
     * @param null $parameters
     */
    public function __construct($parameters = null) {
        $this->initialize($parameters);
    }
    /**
     * @param array|null $parameters
     * @return $this Item
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
     * {@inheritDoc}
     */
    public function getName() {
        return $this->getParameter('name');
    }
    /**
     * Set the item name
     */
    public function setName($value) {
        return $this->setParameter('name', $value);
    }
    /**
     * @return string
     */
    public function getDescription() {
        return $this->getParameter('description');
    }
    /**
     * @param $value
     * @return $this|\Notadd\Payment\Common\Item
     */
    public function setDescription($value) {
        return $this->setParameter('description', $value);
    }
    /**
     * @return mixed
     */
    public function getQuantity() {
        return $this->getParameter('quantity');
    }
    /**
     * @param $value
     * @return $this|\Notadd\Payment\Common\Item
     */
    public function setQuantity($value) {
        return $this->setParameter('quantity', $value);
    }
    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->getParameter('price');
    }
    /**
     * @param $value
     * @return $this|\Notadd\Payment\Common\Item
     */
    public function setPrice($value) {
        return $this->setParameter('price', $value);
    }
}