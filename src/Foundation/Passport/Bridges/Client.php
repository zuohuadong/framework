<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:48
 */
namespace Notadd\Foundation\Passport\Bridges;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\ClientEntityInterface;
/**
 * Class Client
 * @package Notadd\Foundation\Passport\Bridges
 */
class Client implements ClientEntityInterface {
    use ClientTrait, EntityTrait;
    /**
     * Client constructor.
     * @param $identifier
     * @param $name
     * @param $redirectUri
     */
    public function __construct($identifier, $name, $redirectUri) {
        $this->setIdentifier($identifier);
        $this->name = $name;
        $this->redirectUri = $redirectUri;
    }
}