<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:26
 */
namespace Notadd\Payment\Exceptions;
use Notadd\Payment\Contracts\Exception;
/**
 * Class BadMethodCallException
 * @package Notadd\Payment\Exceptions
 */
class BadMethodCallException extends \BadMethodCallException implements Exception {
}