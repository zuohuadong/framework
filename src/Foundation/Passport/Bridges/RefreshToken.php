<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:53
 */
namespace Notadd\Foundation\Passport\Bridges;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
/**
 * Class RefreshToken
 * @package Notadd\Foundation\Passport\Bridges
 */
class RefreshToken implements RefreshTokenEntityInterface {
    use EntityTrait, RefreshTokenTrait;
}