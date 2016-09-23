<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:45
 */
namespace Notadd\Foundation\Passport\Bridges;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
/**
 * Class AuthCode
 * @package Notadd\Foundation\Passport\Bridges
 */
class AuthCode implements AuthCodeEntityInterface {
    use AuthCodeTrait, EntityTrait, TokenEntityTrait;
}