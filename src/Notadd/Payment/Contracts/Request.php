<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:48
 */
namespace Notadd\Payment\Contracts;
/**
 * Interface Request
 * @package Notadd\Payment\Contracts
 */
interface Request extends Message {
    /**
     * @param array $parameters
     */
    public function initialize(array $parameters = []);
    /**
     * @return array
     */
    public function getParameters();
    /**
     * @return ResponseInterface
     */
    public function getResponse();
    /**
     * @return ResponseInterface
     */
    public function send();
    /**
     * @param  mixed $data
     * @return ResponseInterface
     */
    public function sendData($data);
}