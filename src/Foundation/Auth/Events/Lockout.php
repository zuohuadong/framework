<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 16:12
 */
namespace Notadd\Foundation\Auth\Events;
use Psr\Http\Message\ServerRequestInterface as Request;
/**
 * Class Lockout
 * @package Notadd\Foundation\Auth\Events
 */
class Lockout {
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    public $request;
    /**
     * Lockout constructor.
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }
}