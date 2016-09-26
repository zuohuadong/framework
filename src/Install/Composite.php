<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 18:48
 */
namespace Notadd\Install;
use Notadd\Install\Contracts\Prerequisite;
/**
 * Class Composite
 * @package Notadd\Install
 */
class Composite implements Prerequisite {
    /**
     * @var array
     */
    protected $prerequisites = [];
    /**
     * Composite constructor.
     * @param \Notadd\Install\Contracts\Prerequisite $first
     */
    public function __construct(Prerequisite $first) {
        foreach(func_get_args() as $prerequisite) {
            $this->prerequisites[] = $prerequisite;
        }
    }
    /**
     * @return mixed
     */
    public function check() {
        return array_reduce($this->prerequisites, function ($previous, Prerequisite $prerequisite) {
            return $prerequisite->check() && $previous;
        }, true);
    }
    /**
     * @return array
     */
    public function getErrors() {
        return collect($this->prerequisites)->map(function (Prerequisite $prerequisite) {
            return $prerequisite->getErrors();
        })->reduce('array_merge', []);
    }
}