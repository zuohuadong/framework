<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:34
 */
namespace Notadd\Foundation\Passport;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
/**
 * Class PersonalAccessTokenResult
 * @package Notadd\Foundation\Passport
 */
class PersonalAccessTokenResult implements Arrayable, Jsonable {
    /**
     * @var string
     */
    public $accessToken;
    /**
     * @var \Notadd\Foundation\Passport\Token
     */
    public $token;
    /**
     * PersonalAccessTokenResult constructor.
     * @param $accessToken
     * @param $token
     */
    public function __construct($accessToken, $token) {
        $this->token = $token;
        $this->accessToken = $accessToken;
    }
    /**
     * @return array
     */
    public function toArray() {
        return [
            'accessToken' => $this->accessToken,
            'token' => $this->token,
        ];
    }
    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0) {
        return json_encode($this->toArray(), $options);
    }
}