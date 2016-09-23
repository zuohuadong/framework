<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:55
 */
namespace Notadd\Foundation\Passport\Bridges;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;
/**
 * Class User
 * @package Notadd\Foundation\Passport\Bridges
 */
class User implements UserEntityInterface {
    use EntityTrait;
    /**
     * User constructor.
     * @param $identifier
     */
    public function __construct($identifier) {
        $this->setIdentifier($identifier);
    }
}