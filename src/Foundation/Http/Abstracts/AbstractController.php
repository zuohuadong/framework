<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-26 16:04
 */
namespace Notadd\Foundation\Http\Abstracts;
use Notadd\Foundation\Http\Contracts\ControllerContract;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Class AbstractController
 * @package Notadd\Foundation\Http\Abstracts
 */
abstract class AbstractController implements ControllerContract {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return string
     */
    public function handle(Request $request) {
        return '';
    }
}