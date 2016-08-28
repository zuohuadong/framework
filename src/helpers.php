<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-20 01:40
 */
use Illuminate\Container\Container;
if(!function_exists('app')) {
    /**
     * @param  string $make
     * @param  array $parameters
     * @return \Illuminate\Container\Container
     */
    function app($make = null, $parameters = []) {
        if(is_null($make)) {
            return Container::getInstance();
        }
        return Container::getInstance()->make($make, $parameters);
    }
}
if(!function_exists('app_path')) {
    /**
     * @param  string $path
     * @return string
     */
    function app_path($path = '') {
        return app('path') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if(!function_exists('base_path')) {
    /**
     * @param  string $path
     * @return string
     */
    function base_path($path = '') {
        return app()->basePath() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if(!function_exists('public_path')) {
    /**
     * @param  string $path
     * @return string
     */
    function public_path($path = '') {
        return app()->make('path.public') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if(!function_exists('storage_path')) {
    /**
     * @param  string $path
     * @return string
     */
    function storage_path($path = '') {
        return app('path.storage') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if(!function_exists('event')) {
    /**
     * @param  string|object $event
     * @param  mixed $payload
     * @param  bool $halt
     * @return array|null
     */
    function event($event, $payload = [], $halt = false) {
        return app('events')->fire($event, $payload, $halt);
    }
}