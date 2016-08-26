<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-26 15:16
 */
namespace Notadd\Foundation\Http\Contracts;
use Psr\Http\Message\ServerRequestInterface;
/**
 * Interface ControllerContract
 * @package Notadd\Foundation\Http\Contracts
 */
interface ControllerContract {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request);
}