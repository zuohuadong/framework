<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:35
 */
namespace Notadd\Foundation\Passport;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
/**
 * Class Scope
 * @package Notadd\Foundation\Passport
 */
class Scope implements Arrayable, Jsonable {
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $description;
    /**
     * Scope constructor.
     * @param $id
     * @param $description
     */
    public function __construct($id, $description) {
        $this->id = $id;
        $this->description = $description;
    }
    /**
     * @return array
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'description' => $this->description,
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