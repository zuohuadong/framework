<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-12 17:50
 */
namespace Notadd\Foundation\Routing\Traits;
use Illuminate\Support\Str;
/**
 * Class ResolveClassMethodTrait
 * @package Notadd\Foundation\Routing\Traits
 */
trait ResolveClassMethodTrait {
    /**
     * @param $handler
     * @return array
     */
    protected function resolveClassMethod($handler) {
        $method = 'handle';
        if(Str::contains($handler, '@')) {
            $segments = explode('@', $handler);
            $class = $segments[0];
            $method = $segments[1];
        } else {
            $class = $handler;
        }
        return [$class, $method];
    }
}