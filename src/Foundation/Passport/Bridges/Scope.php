<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:54
 */
namespace Notadd\Foundation\Passport\Bridges;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
/**
 * Class Scope
 * @package Notadd\Foundation\Passport\Bridges
 */
class Scope implements ScopeEntityInterface {
    use EntityTrait;
    /**
     * Scope constructor.
     * @param $name
     */
    public function __construct($name) {
        $this->setIdentifier($name);
    }
    /**
     * @return mixed
     */
    public function jsonSerialize() {
        return $this->getIdentifier();
    }
}