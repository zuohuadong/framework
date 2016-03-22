<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:13
 */
namespace Notadd\Payment\Commons;
/**
 * Class PaymentMethod
 * @package Notadd\Payment\Commons
 */
class PaymentMethod {
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @param string $id
     * @param string $name
     */
    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
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
}