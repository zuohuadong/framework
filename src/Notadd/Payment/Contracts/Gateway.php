<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:09
 */
namespace Notadd\Payment\Contracts;
/**
 * Interface Gateway
 * @package Notadd\Payment\Contracts
 */
interface Gateway {
    /**
     * @return string
     */
    public function getName();
    /**
     * @return string
     */
    public function getShortName();
    /**
     * @return mixed
     */
    public function getDefaultParameters();
    /**
     * @param array $parameters
     * @return mixed
     */
    public function initialize(array $parameters = array());
    /**
     * @return mixed
     */
    public function getParameters();
}